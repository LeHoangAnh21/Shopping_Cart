<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @IsGranted("ROLE_USER")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author_index")
     */
    public function authorIndex()
    {
        $authors = $this->getDoctrine()->getRepository(Author::class)->findAll();

        return $this->render(
            'author/index.html.twig', 
            [
                'authors' => $authors
            ]
        );
    }

    /**
     * @Route("/author/detail/{id}", name="author_detail")
     */
    public function authorDetail($id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        if($author == null){
            $this->addFlash('Error', 'Author not found!');
            return $this->redirectToRoute('author_index');
        }
        else{
            return $this->render(
                'author/detail.html.twig', 
                [
                    'author' => $author
                ]
            );
        }
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/author/delete/{id}", name="author_delete")
     */
    public function deleteAuthor($id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        if($author == null){
            $this->addFlash('Error', 'Author not found!');
        }
        else{
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($author);
            $manager->flush();
            $this->addFlash('Success', 'Author has been deleted!');
        }
        return $this->redirectToRoute('author_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/author/add", name="author_add")
     */
    public function addAuthor(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $image = $author->getImage();
            $imgName = uniqid();
            $imgExtension = $image->guessExtension();
            $imageName = $imgName . "." . $imgExtension;

            try{
                $image->move(
                    $this->getParameter('author_image'), $imageName
                );
            }
            catch(FileException $e){

            }
            $author->setImage($imageName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($author);
            $manager->flush();

            $this->addFlash('Success', "Add author successfully !");
            return $this->redirectToRoute("author_index");
        }

        return $this->render(
            "author/add.html.twig",
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("author/edit/{id}", name="author_edit")
     */
    public function editAuthor(Request $request, $id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        
        if ($author == null) 
        {
            $this->addFlash('Error', 'Author not found !');
            return $this->redirectToRoute('author_index');
        } 
        else 
        { 
            $form = $this->createForm(AuthorType::class, $author);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) 
            {
                $file = $form['image']->getData();

                if ($file != null)
                {
                    $image = $author->getImage();
                    $imgName = uniqid();
                    $imgExtension = $image->guessExtension();
                    $imageName = $imgName . "." . $imgExtension;

                    try {
                        $image->move(
                            $this->getParameter('author_image'), $imageName
                        );
                    } 
                    catch (FileException $e) {

                    }

                    $author->setImage($imageName);
                }
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($author);
                    $manager->flush();

                    $this->addFlash('Success', "Update author successfully !");
                    return $this->redirectToRoute("author_index");
            }

            return $this->render(
                "author/edit.html.twig",
                [
                    'form' => $form->createView()
                ]
            );
        }
    }
}
