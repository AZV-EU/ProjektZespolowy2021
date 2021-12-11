<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $tasksRepository;
    private $entityManager;
    private $flashBag;

    public function __construct(
        TasksRepository        $tasksRepository,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag
    ) {
        $this->tasksRepository = $tasksRepository;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator =  $this->tasksRepository->getUserTaskPagination($offset);

        return $this->render('home/index.html.twig',[
            'tasks' => $paginator,
            'previous' => $offset - TasksRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + TasksRepository::PAGINATOR_PER_PAGE),
        ]);
    }

    /**
     * @Route("task/{task}",name="task")
     */
    public function task(Request $request,Tasks $task)
    {
        return $this->render('home/task.html.twig',[
            'task' => $task
        ]);
    }

    /**
     * @Route("done/{task}",name="done")
     */
    public function taskdone(Tasks $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->flashBag->add('success','Task was mark on done ');

        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/test")
     */
    public function test()
    {

        $this->getUser();
        $ob = $this->repository->findOneBy(['id' => 3]);

        $ob->setName("testttt");

        $this->entityManager->persist($ob);
        $this->entityManager->flush();

        return $this->render('home/index.html.twig');

    }
}
