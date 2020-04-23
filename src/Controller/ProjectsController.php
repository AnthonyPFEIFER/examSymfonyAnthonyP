<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProjectsController extends AbstractController
{
    /**
     * @Route("/manager/projects", name="projects-list")
     */
    public function projectsList(Request $request, SerializerInterface $serializer)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepository->findAll();

        $taskRepository = $this->getDoctrine()->getRepository(Task::class);


        return $this->render('projects/projects-list.html.twig', [
            'controller_name' => 'ManagerController',
            'projects' => $projects
        ]);
    }
    /**
     * @Route("/manager/project/{id}", name="project-detail")
     */
    public function projectDetail(Request $request, SerializerInterface $serializer)
    {
        return $this->render('projects/project-detail.html.twig', [
            'controller_name' => 'ManagerController'
        ]);
    }
}
