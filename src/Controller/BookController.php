<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\throwException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_index")
     */
    public function bookIndex()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

        if ($books == null) {
            $this->addFlash('Error', 'Book list is empty');
        }
        return $this->render(
            'book/index.html.twig',
            [
                'books' => $books
            ]
        );
    }

    /**
     * @Route("/book/detail/{id}", name="book_detail")
     */
    public function bookDetail($id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        if ($book == null) {
            $this->addFlash('Error', 'Book not found');
            return $this->redirectToRoute('book_index');
        } else {
            return $this->render(
                'book/detail.html.twig',
                [
                    'book' => $book
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("book/delete/{id}", name="book_delete")
     */
    public function deleteBook($id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        if ($book == null) 
        {
            $this->addFlash('Error', 'Book not found');
        } 
        else 
        {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($book);
            $manager->flush();
            $this->addFlash('Success', 'Book has been deleted');
        }
        return $this->redirectToRoute('book_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("book/add", name="book_add")
     */
    public function addBook(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $book->getImage();
            $imgName = uniqid();
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . "." . $imgExtension;

            try {
                $image->move(
                    $this->getParameter('book_image'), $imageName
                );
            } catch (FileException $e) {
                throwException($e);
            }
            
            $book->setImage($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($book);
            $manager->flush();

            $this->addFlash('Success', "Add book successfully !");
            return $this->redirectToRoute("book_index");
        }

        return $this->render(
            "book/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("book/edit/{id}", name="book_edit")
     */
    public function editBook(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);

        if ($book == null) 
        {
            $this->addFlash('Error', 'Book not found');
            return $this->redirectToRoute('book_index');
        } 
        else 
        { 
            $form = $this->createForm(BookType::class, $book);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['image']->getData();

                if ($file != null) 
                {
                    $image = $book->getImage();
                    $imgName = uniqid(); 
                    $imgExtension = $image->guessExtension();
                    $imageName = $imgName . "." . $imgExtension;

                    try {
                        $image->move(
                            $this->getParameter('book_image'), $imageName
                        );
                    } catch (FileException $e) {
                        throwException($e);
                    }
                    
                    $book->setImage($imageName);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($book);
                $manager->flush();

                $this->addFlash('Success', "Edit book successfully !");
                return $this->redirectToRoute("book_index");
            }

            return $this->render(
                "book/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}
