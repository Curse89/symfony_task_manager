<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupFormType;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @Route("/group", name="group_index")
     */
    public function index(): Response
    {
		$groups = $this->getDoctrine()
			->getRepository(Group::class)
			->findAllGroups();

        return $this->render('public/group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

	/**
	 * @Route("/group/add", name="group_create")
	 */
	public function createGroup(Request $request): Response
	{
		$group = new Group();

		$form = $this->createForm(GroupFormType::class, $group);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$group = $form->getData();
			$group->setCreatedAt();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($group);
			$entityManager->flush();

			$this->addFlash('success', 'Группа успешно добавлена!');

			return $this->redirectToRoute("group_create");
		}

		return $this->renderForm('public/group/add.html.twig', [
			'form' => $form,
			'title' => 'Добавить группу'
		]);
	}

	/**
	 * @Route("/group/update/{title}", name="group_update")
	 */
	public function updateGroup($title, GroupRepository $groupRepository, Request $request): Response
	{
		$group = $groupRepository->findOneBy(['title' => $title]);

		if (!$group) {
			throw $this->createNotFoundException(
				'Группы с таким названием не существует'
			);
		}

		$form = $this->createForm(GroupFormType::class, $group);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$group = $form->getData();
			$group->setUpdatedAt();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($group);
			$entityManager->flush();

			$this->addFlash('success', 'Группа успешно отредактирована!');

			return $this->redirectToRoute("group_index");
		}

		return $this->renderForm('public/group/add.html.twig', [
			'form' => $form,
			'title' => 'Редактировать группу'
		]);
	}

	/**
	 * @Route("/group/delete/{title}", name="group_delete")
	 */
	public function deleteGroup($title, GroupRepository $groupRepository)
	{
		$group = $groupRepository->findOneBy(['title' => $title]);

		if (!$group) {
			throw $this->createNotFoundException(
				'Группы с таким названием не существует'
			);
		}

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($group);
		$entityManager->flush();

		$this->addFlash('success', 'Группа успешно удалена!');

		return $this->redirectToRoute("group_index");
	}
}
