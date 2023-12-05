<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Importez la classe Request
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author; // Importez la classe Author
use Doctrine\ORM\EntityManagerInterface;
use App\Form\AuthorType; // Importez le formulaire AuthorType
use App\Repository\AuthorRepository;

class AuthorController extends AbstractController
{
    #[Route('/showAuthor/{name}', name: 'app_author')]
    public function index($name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/list', name: 'app_author_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        // Récupérez tous les auteurs depuis la base de données
        $authorRepository = $entityManager->getRepository(Author::class);
        $authors = $authorRepository->findAll();

        // Vérifiez si des auteurs ont été trouvés
        if (empty($authors)) {
            return $this->render('author/empty.html.twig');
        }

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/{id}', name: 'app_author_details')]
    public function authorDetails($id, EntityManagerInterface $entityManager): Response
    {
        // Récupérez un auteur par son ID depuis la base de données
        $authorRepository = $entityManager->getRepository(Author::class);
        $author = $authorRepository->find($id);

        // Vérifiez si l'auteur a été trouvé
        if ($author === null) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

        return $this->render('author/details.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/affichage', name: 'app_author_list_display')]
    public function listDisplay(EntityManagerInterface $entityManager): Response
    {
        // Récupérez tous les auteurs depuis la base de données
        $authorRepository = $entityManager->getRepository(Author::class);
        $authors = $authorRepository->findAll();

        // Vérifiez si des auteurs ont été trouvés
        if (empty($authors)) {
            return $this->render('author/empty.html.twig');
        }

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authors', name: 'app_author_list_all')]
    public function listAuthors(): Response
    {
        // Récupérez tous les auteurs depuis la base de données
        $authorRepository = $this->getDoctrine()->getRepository(Author::class);
        $authors = $authorRepository->findAll();

        // Vérifiez s'il y a des auteurs
        if (empty($authors)) {
            return $this->render('author/empty.html.twig');
        }

        // Rendez la vue avec la liste des auteurs
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/add-author', name: 'app_add_author')]
    public function addAuthor(Request $request): Response
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('app_author_list'); 
        }

        return $this->render('author/add_author.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/author/edit/{id}', name: 'app_edit_author', methods: ['GET', 'POST'])]
    public function editAuthor($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'auteur à modifier depuis la base de données
        $authorRepository = $entityManager->getRepository(Author::class);
        $author = $authorRepository->find($id);
    
        // Vérifiez si l'auteur a été trouvé
        if ($author === null) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }
    
        // Créez le formulaire de modification
        $form = $this->createForm(AuthorType::class, $author);
    
        // Gérez la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire est soumis et valide, enregistrez les modifications dans la base de données
            $entityManager->flush();
    
            // Redirigez où vous le souhaitez après la modification
            return $this->redirectToRoute('app_author_list');
        }
    
        // Affichez le formulaire de modification
        return $this->render('author/edit_author.html.twig', [
            'form' => $form->createView(),
            'author' => $author, // Vous pouvez transmettre l'auteur à la vue pour l'affichage de ses informations
        ]);
    }

    #[Route('/author/delete/{id}', name: 'app_delete_author')]
public function deleteAuthor($id, EntityManagerInterface $entityManager): Response
{
    $authorRepository = $entityManager->getRepository(Author::class);
    $author = $authorRepository->find($id);

    if ($author === null) {
        throw $this->createNotFoundException('Auteur non trouvé');
    }

    $entityManager->remove($author);
    $entityManager->flush();

    return $this->redirectToRoute('app_author_list');
}

#[Route('/tri', name: 'app_tri_authors')]
public function triQB(AuthorRepository $repository)

{
    $authors = $repository->triQB();

    return $this->render('author/tri.html.twig', [
        'authors' => $authors,
    ]);
}
}