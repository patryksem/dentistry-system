<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/department")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/", name="department_index", methods={"GET"})
     */
    public function index(DepartmentRepository $departmentRepository): Response
    {
        return $this->render('department/index.html.twig', [
            'departments' => $departmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/timetable", name="department_timetable", methods={"GET"})
     */
    public function timetable(DepartmentRepository $repository) {

        $department = $repository->findOneBy(['active' => 1]);

        if ($department === null) {
            return $this->json([
                'startDate' => "now",
                "endDate" => "+1m",
                "daysOfWeekDisabled" => "0,1,2,3,4,5,6"
            ]);
        }

        return $this->json([
            'startDate' => $department->isStillOpen() ? "now" : "+1d",
            "endDate" => "+1m",
            "daysOfWeekDisabled" => implode(",", $department->getClosedDaysOfWeek())
        ]);
    }

    /**
     * @Route("/new", name="department_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($department->getActive() === true) {
                $q = $entityManager->createQuery("update App\Entity\Department m set m.active = 0 WHERE m.active = 1");
                $q->execute();
            }

            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/new.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="department_show", methods={"GET"})
     */
    public function show(Department $department): Response
    {
        return $this->render('department/show.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="department_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Department $department, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $q = $em->createQuery("update App\Entity\Department m set m.active = 0 WHERE m.active = 1");
            $q->execute();
            $em->persist($department);
            $em->flush();

            if ($department->getActive() === true) {
                $q = $em->createQuery("update App\Entity\Department m set m.active = 1 WHERE m.id = ".$department->getId());
                $q->execute();
            }


            return $this->redirectToRoute('department_index');
        }

        return $this->render('department/edit.html.twig', [
            'department' => $department,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="department_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Department $department): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($department);
            $entityManager->flush();
        }

        return $this->redirectToRoute('department_index');
    }


}
