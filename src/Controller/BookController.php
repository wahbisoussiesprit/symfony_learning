<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookRepository;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/add-book', name: 'app_add_book')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $book->setPublished(true); // Initialize 'published' as true
    
        $form = $this->createForm(BookType::class, $book);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $book->getAuthor();
            $author->addNbbook($book);
    
            $entityManager->persist($book);
            $entityManager->persist($author);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_book'); 
        }
    
        return $this->render('book/add_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/published-books', name: 'app_published_books')]
    public function publishedBooks(BookRepository $bookRepository): Response
    {
        $publishedBooks = $bookRepository->findPublishedBooks();

        return $this->render('book/published_books.html.twig', [
            'publishedBooks' => $publishedBooks,
        ]);
    }

    #[Route('/edit-book/{id}', name: 'app_edit_book')]
    public function editBook(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $book = $entityManager->getRepository(Book::class)->find($id);
    
        if (!$book) {
            // Handle the case where the book with the given ID is not found
            // You can throw an exception or redirect to an error page.
        }
    
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_book');
        }
    
        return $this->render('book/edit_book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-book/{id}', name: 'app_delete_book')]
public function deleteBook(EntityManagerInterface $entityManager, $id): RedirectResponse
{
    $book = $entityManager->getRepository(Book::class)->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Book not found');
    }

    $entityManager->remove($book);
    $entityManager->flush();

    return $this->redirectToRoute('app_published_books');
}

#[Route('/show-book', name: 'app_show_book')]
public function showBook(BookRepository $bookRepository): Response
{
    $book = $bookRepository->findOneBy([], ['id' => 'ASC']);

    if (!$book) {
        throw $this->createNotFoundException('No books found');
    }

    return $this->render('book/show_book.html.twig', [
        'book' => $book,
    ]);
}

//

#[Route('/search-book', name: 'app_search_book')]
public function searchBook(Request $request): Response
{
    return $this->render('book/search_ref.html.twig');
}

}