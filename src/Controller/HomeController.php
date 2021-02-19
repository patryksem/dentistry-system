<?php


namespace App\Controller;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(DepartmentRepository $repository)
    {
        /** @var Department $department */
        $department = $repository->findOneByActive(1);

       return $this->render('homepage.html.twig', [
           'isOpen' => $department === null ? null : $department->isOpenToday(),
           'workingHours' => $department === null ? null : $department->getWorkHours(),
       ]);
    }

}