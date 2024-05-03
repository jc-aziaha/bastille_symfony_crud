<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/', name: 'app_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('pages/index.html.twig', [
            "contacts" => $contacts
        ]);
    }


    #[Route('/create', name: 'app_contact_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1- Créons l'instance du contact qui doit être ajouté en base de données
        $contact = new Contact();

        // 2- Créons le formulaire en se basant sur son type
        $form = $this->createForm(ContactFormType::class, $contact);

        // 4- Associer les données de la requête aux données du formulaire
        $form->handleRequest($request); 

        // 5- Si le formulaire est soumis et que le formulaire est valide,
        if ( $form->isSubmitted() && $form->isValid() ) 
        {

            // Initialisons les dates de création et de modification(non obligatoire)
            $contact->setCreatedAt(new DateTimeImmutable());
            $contact->setUpdatedAt(new DateTimeImmutable());

            // Demander au manager des entités de préparer la requête d'insertion du nouveau contact en base de données
            $entityManager->persist($contact);

            // Exécuter la requête
            $entityManager->flush();

            // Générer un message flash de succès
            $this->addFlash("success", "Le contact a été ajouté avec succès.");

            // Effectuer une redirection vers la page d'accueil afin de consulter le nouveau contact ajouté dans la liste
            // Puis arrêter l'exécution du script
            return $this->redirectToRoute('app_contact_index');
        }

        // 3- Passons la partie visible du formulaire à la page(vue) pour affichage
        return $this->render("pages/create.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route('/{id<\d+>}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(
        string $id, 
        ContactRepository $contactRepository, 
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        // 1- Vérifions si le contact à modifier existe vraiment dans la base de données ou non
        $contact = $contactRepository->find($id);

        // 2- Si le contact n'est pas trouvé,
        if ( null === $contact ) 
        {
            // Effectuons une redirection vers la page d'accueil
                // Puis arrêtons l'exécution du script.
            return $this->redirectToRoute('app_contact_index');
        }


        // Dans le cas contraire,
        // 3- Générons le formulaire de modification du contact
        $form = $this->createForm(ContactFormType::class, $contact);

        
        // 5- Associer les données de la requête aux données du formulaire
        $form->handleRequest($request);


        // 6- Si le formulaire est soumis et que le formulaire est valide,
        if ( $form->isSubmitted() && $form->isValid() ) 
        {

            // Mettre à jour la date de modification de ce contact
            $contact->setUpdatedAt(new DateTimeImmutable());

            // Demander au manager des entités de préparer la requête de modification du contact en base de données
            $em->persist($contact);
            
            // Exécuter la requête
            $em->flush();
            
            // Générer un message flash de succès
            $this->addFlash("success", "Le contact a été modifié avec succès.");

            // Effectuer une redirection vers la page d'accueil
                // Puis arrêter l'exécution du script.
            return $this->redirectToRoute('app_contact_index');
        }

          

        // 4- Passons la partie visible du formulaire à la page (vue)
        return $this->render("pages/edit.html.twig", [
            "form" => $form->createView()
        ]);
    }


    #[Route('/{id<\d+>}/delete', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(
        string $id, 
        ContactRepository $contactRepository, 
        Request $request,
        EntityManagerInterface $em
    ): Response
    {

        // 1- Vérifions si le contact à modifier existe vraiment dans la base de données ou non
        $contact = $contactRepository->find($id);

        // 2- Si le contact n'est pas trouvé,
        if ( null === $contact ) 
        {
            // Effectuons une redirection vers la page d'accueil
                // Puis arrêtons l'exécution du script.
            return $this->redirectToRoute('app_contact_index');
        }

        // 3- Si le jéton de sécurité pour se protéger contre les failles de type csrf est valide,
        if ( $this->isCsrfTokenValid("delete_contact_{$contact->getId()}", $request->request->get('_csrf_token') ) )
        {
            // 4- Demander au manager des entités de préparer la requête de suppression du contact en base de données
            $em->remove($contact);

            // 5- Exécuter la requête
            $em->flush();

            // Générer un message flash de succès
            $this->addFlash("success", "Le contact a été supprimé avec succès.");
        }

        // 6- Effectuer une redirection vers la page d'accueil
            // Puis arrêter l'exécution du script.
        return $this->redirectToRoute('app_contact_index');
    }


}