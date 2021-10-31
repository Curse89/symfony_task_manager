<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupFormType;
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

		//dd($groups);


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
		if ($form->isSubmitted() && $form->isValid()) {
			$group = $form->getData();
			$group->setCreatedAt();

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($group);
			$entityManager->flush();

			return $this->redirectToRoute("group_create");
		}
//		$group->setTitle('test');
//		$group->setActive(true);

		return $this->renderForm('group/add.html.twig', [
			'form' => $form
		]);
	}
}
