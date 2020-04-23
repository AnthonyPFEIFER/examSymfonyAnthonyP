<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/projects", name="api-projects")
     */
    public function ProjectList(Request $request, SerializerInterface $serializer)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepository->findAll();
        $serializedProjects = $serializer->serialize($projects, 'json');
        return new JsonResponse($serializedProjects, 200, [], true);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/project/{id}/tasks", name="api-tasks")
     */
    public function TasksInAProject(Request $request, SerializerInterface $serializer, $id)
    {

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);
        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $taskRepository->findBy(['project_id' => $id]);

        $serializedTasksByProject = $serializer->serialize($tasks, 'json');
        return new JsonResponse($serializedTasksByProject, 200, [], true);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
