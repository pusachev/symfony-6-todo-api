<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/tasks')]
class TaskController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    #[Route('', name: 'task_list', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function list(
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $tasks = $this->getUser()->getTasks();
        $data = $serializer->serialize($tasks, 'json');

        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    #[Route('', name: 'task_create', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $task = $serializer->deserialize($request->getContent(), Task::class, 'json');
        $task->setUser($this->getUser());
        $em->persist($task);
        $em->flush();

        $data = $serializer->serialize(
            $task,
            'json',
            [
                'groups' => 'task:read'
            ]
        );

        return new JsonResponse(
            $data,
            201,
            [],
            true
        );
    }

    /**
     * @param Task $task
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'task_view', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function view(
        Task $task,
        SerializerInterface $serializer
    ): JsonResponse
    {
        if ($task->getUser() !== $this->getUser()) {
            throw new AccessDeniedHttpException();
        }

        return new JsonResponse(
            $serializer->serialize(
                $task,
                'json',
                [
                    'groups' => 'task:read'
                ]
            ),
            200,
            [],
            true
        );
    }

    /**
     * @param Request $request
     * @param Task $task
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'task_update', methods: ['PUT'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function update(
        Request $request,
        Task $task,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): JsonResponse
    {
        if ($task->getUser() !== $this->getUser()) {
            throw new AccessDeniedHttpException();
        }

        $data = json_decode($request->getContent(), true);

        $task->setTitle($data['title'] ?? $task->getTitle());
        $task->setDescription($data['description'] ?? $task->getDescription());
        $task->setComplete($data['complete'] ?? $task->isComplete());

        $em->flush();

        return new JsonResponse(
            $serializer->serialize(
                $task,
                'json',
                [
                    'groups' => 'task:read'
                ]
            ),
            200,
            [],
            true
        );
    }

    /**
     * @param Task $task
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'task_delete', methods: ['DELETE'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(
        Task $task,
        EntityManagerInterface $em
    ): JsonResponse
    {
        if ($task->getUser() !== $this->getUser()) {
            throw new AccessDeniedHttpException();
        }

        $em->remove($task);
        $em->flush();

        return new JsonResponse(
            null,
            204
        );
    }
}
