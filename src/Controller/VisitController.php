<?php

namespace App\Controller;

use App\Entity\Visit;
use App\Form\VisitForm;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/visit')]
final class VisitController extends AbstractController
{
    #[Route(name: 'app_visit_index', methods: ['GET'])]
    public function index(VisitRepository $visitRepository): Response
    {
        return $this->render('visit/index.html.twig', [
            'visits' => $visitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_visit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $visit = new Visit();
        $form = $this->createForm(VisitForm::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l’upload des photos
            $pictures = $form->get('pictures')->getData();
            $picturePaths = [];

            foreach ($pictures as $picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'), // doit être défini dans services.yaml
                        $newFilename
                    );
                    $picturePaths[] = $newFilename;
                } catch (FileException $e) {
                    // Gestion de l'erreur
                }
            }

            // Enregistre les chemins des images en JSON
            $visit->setPictures($picturePaths); // Assure-toi que le champ est de type json dans l'entité

            $em->persist($visit);
            $em->flush();

            return $this->redirectToRoute('app_visit_index');
        }

        return $this->render('visit/new.html.twig', [
            'visit' => $visit,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_visit_show', methods: ['GET'])]
    public function show(Visit $visit): Response
    {
        return $this->render('visit/show.html.twig', [
            'visit' => $visit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_visit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visit $visit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VisitForm::class, $visit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $uploadedPictures */
            $uploadedPictures = $form->get('pictures')->getData();

            $pictureFilenames = $visit->getPictures() ?? [];

            if ($uploadedPictures) {
                foreach ($uploadedPictures as $picture) {
                    $newFilename = uniqid() . '.' . $picture->guessExtension();
                    $picture->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $pictureFilenames[] = $newFilename;
                }
            }

            $visit->setPictures($pictureFilenames);

            $entityManager->flush();

            return $this->redirectToRoute('app_visit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('visit/edit.html.twig', [
            'visit' => $visit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_visit_delete', methods: ['POST'])]
    public function delete(Request $request, Visit $visit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $visit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($visit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_visit_index', [], Response::HTTP_SEE_OTHER);
    }
}
