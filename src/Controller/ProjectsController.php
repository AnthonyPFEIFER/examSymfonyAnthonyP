<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\AddProjectType;
use App\Form\AddTaskType;
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
     * @Route("/manager/project/add", name="project-add")
     */
    public function projectAdd(Request $request, SerializerInterface $serializer)
    {
        $project = new Project();

        $projectForm = $this->createForm(AddProjectType::class, $project);
        $projectForm->handleRequest($request);

        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $project->setStartedAt(new \DateTime());
            $project->setStatus("Nouveau");

            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('projects-list');
        }

        return $this->render('projects/project-add.html.twig', [
            'projectForm' => $projectForm->createView()
        ]);
    }
    /**
     * @Route("/manager/project/{id}", name="project-detail")
     */
    public function projectDetail(Request $request, SerializerInterface $serializer, $id)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->find($id);
        $projects = $projectRepository->findAll();
        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $taskRepository->findBy(['project_id' => $id]);


        return $this->render('projects/project-detail.html.twig', [
            'controller_name' => 'ManagerController',
            'project' => $project,
            'projects' => $projects,
            'tasks' => $tasks
        ]);
    }
    /**
     * @Route("/manager/project/{id}/task/add", name="task-add")
     */
    public function taskAdd(Request $request, SerializerInterface $serializer, $id)
    {

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->findOneBy(['id' => $id]);

        $task = new Task();
        $taskForm = $this->createForm(AddTaskType::class, $task);
        $taskForm->handleRequest($request);

        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $task->setCreatedAt(new \DateTime());
            $task->setProjectId($project);

            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('projects/task-add.html.twig', [
            'taskForm' => $taskForm->createView(),
            'project' => $project
        ]);
    }
}
