<?php

namespace App\Controller;

use App\Entity\Associer;
use App\Form\AssocierForm;
use App\Repository\AssocierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/associer')]
final class AssocierController extends AbstractController
{
    #[Route(name: 'app_associer_index', methods: ['GET'])]
    public function index(AssocierRepository $associerRepository): Response
    {
        return $this->render('associer/index.html.twig', [
            'associers' => $associerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_associer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $associer = new Associer();
        $form = $this->createForm(AssocierForm::class, $associer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($associer);
            $entityManager->flush();

            return $this->redirectToRoute('app_associer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('associer/new.html.twig', [
            'associer' => $associer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_associer_show', methods: ['GET'])]
    public function show(Associer $associer): Response
    {
        return $this->render('associer/show.html.twig', [
            'associer' => $associer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_associer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Associer $associer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssocierForm::class, $associer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_associer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('associer/edit.html.twig', [
            'associer' => $associer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_associer_delete', methods: ['POST'])]
    public function delete(Request $request, Associer $associer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$associer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($associer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_associer_index', [], Response::HTTP_SEE_OTHER);
    }
}
