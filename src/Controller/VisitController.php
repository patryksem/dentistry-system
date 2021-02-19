<?php


namespace App\Controller;

use App\Entity\Visit;
use App\Form\VisitFormType;
use App\Repository\AttachemntRepository;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use App\Repository\VisitRepository;
use App\Service\CalendarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/visit")
 */
class VisitController extends AbstractController
{
    /**
     * @Route("/doctor", name="visit_doctor_list")
     */
    public function index(UserRepository $repository)
    {
        $visits = $this->getUser()->getVisits();
        $visitCount = 0;

        /** @var Visit $visit */
        foreach ($visits as $visit) {
            if ($visit->getStatus() == 0) {
                $visitCount++;
            }
        }

        $canVisit = $visitCount < 2;

       return $this->render('visits/doctors.html.twig', [
           'doctors' =>  $repository->findOneByRole('DOCTOR'),
           'canVisit' => $canVisit
       ]);
    }


    /**
     * @Route("/remove/{id}", name="visit_remove")
     */
    public function vistiRemove(int $id, VisitRepository $repository, EntityManagerInterface $entityManager)
    {
        $visit = $repository->findOneBy(['id' => $id]);
        $patientId = $visit->getPatientId()->getId();

        $entityManager->remove($visit);
        $entityManager->flush();
        $this->addFlash('success', 'Usunięto wizytyę');

        return $this->redirectToRoute('visit_patient_list', ['id' => $patientId]);
    }

    /**
     * @Route("/control/{id}/finish", name="doctor_accept_visit_finish")
     */
    public function controlFinish(int $id, Request $request, VisitRepository $repository, EntityManagerInterface $entityManager)
    {

        $visist = $repository->findOneBy(["id" => $id]);
        $visist->setStatus(1);
        $visist->setComment($request->request->get('comment'));

        $entityManager->persist($visist);
        $entityManager->flush();
        $this->addFlash('success', 'Zakończono wizytę');
        return $this->redirectToRoute('visit_doctor_patients');
    }

    /**
     * @Route("/control/{id}", name="doctor_accept_visit")
     */
    public function control(int $id, VisitRepository $repository, AttachemntRepository  $attachemntRepository)
    {

        $visist = $repository->findOneBy(["id" => $id]);
        $images = $attachemntRepository->findBy(['user' => $visist->getPatientId()]);

        return $this->render('visits/previewVisit.html.twig', [
            'visit' =>  $visist,
            'files' => $images
        ]);
    }

    /**
     * @Route("/lists/{id}", name="visit_patient_list")
     */
    public function lists(int $id, UserRepository $repository)
    {

        $user = $repository->findOneBy(['id' => $id]);

        $openVisits = [];
        $closedVisits = [];

        foreach ($user->getVisits() as $visit) {
            if ($visit->getStatus() === 0) {
                $openVisits[] = $visit;
            } else {
                $closedVisits[] = $visit;
            }
        }


        krsort($closedVisits);
        return $this->render('visits/lists.html.twig', [
            'openVisits' => $openVisits,
            'closedVisits' => $closedVisits,
            'posNumber' => 0,
            'openVisitsExists' => (bool)count($openVisits)
        ]);
    }

    /**
     * @Route("/doctor/patients", name="visit_doctor_patients")
     */
    public function patientsForDoctor()
    {

        $visitsList = $this->getUser()->getPatients();
        $today = (new \DateTime())->format('Y-m-d');
        $visits = [];
        /** @var Visit $visit */

        foreach ($visitsList as $visit) {
            if ($visit->getVisitDate()->format('Y-m-d') === $today) {
                $visits[] = $visit;
            }
        }
        return $this->render('visits/patientsLists.html.twig', [
            'visits' =>  $visits,
            'showAll' => false
        ]);
    }

    /**
     * @Route("/doctor/patients/all", name="visit_doctor_patients_all")
     */
    public function patientsForDoctorAll()
    {
        return $this->render('visits/patientsLists.html.twig', [
            'visits' =>  $this->getUser()->getPatients(),
            'showAll' => true
        ]);
    }

    /**
     * @Route("/doctor/{id}", name="doctor_available")
     */
    public function doctorAvailable(int $id, Request  $request, UserRepository $repository) {

        $visit = new Visit();
        $form = $this->createForm(VisitFormType::class, $visit);
        $form->handleRequest($request);

//        $date = new \DateTime(sprintf('%s-%s-%s '))


        if ($form->isSubmitted() && $request->isMethod("POST")) {

            $visit->setDoctorId(
                $repository->findOneBy(['id' => $id])
            );
            $visit->setPatientId($this->getUser());

            if ($visit->getDescription() == null) {
                $visit->setDescription('');
            }
            $visit->setStatus(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($visit);
            $entityManager->flush();

            $this->addFlash('success', 'Zapisano na wizytę');
            return $this->redirectToRoute('visit_patient_list', [
                'id' => $this->getUser()->getId()
            ]);
        }


        return $this->render('visits/availableDoctorCalendar.html.twig', [
            'doctor' => $repository->findOneBy(['id' => $id]),
            'form' =>  $form->createView()
        ]);
    }

    /**
     * @Route("/doctor/{id}/{timestamp}", name="doctor_available_days")
     */
    public function doctorAvailableAtDay(int $id, int $timestamp, UserRepository $repository, CalendarService $calendar) {

       $date = new \DateTime(date('Y-m-d H:i:s', $timestamp));
       $date->modify('+1 day');

       $doctor = $repository->findOneBy(['id' => $id]);

       $bookedVisits = $repository->findPatientsBookedAtDate($doctor, $date);

       return $this->json(
           array_keys($calendar->generateAvailableBookSlots($bookedVisits))
       );
    }

}