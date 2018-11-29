<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\AddGenreType;
use App\Form\UpdateGenreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends Controller
{
    /**
     * @Route("/Genre/list", name="Genre_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $genres = $em->getRepository(Genre::class)->findAllFull();

        return $this->render('Genre/list.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/Genre/add", name="Genre_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $genre = new Genre();

        $form = $this->createForm(AddGenreType::class,$genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre successfully saved!');
            return $this->redirectToRoute('Genre_list');
        }

        return $this->render('Genre/add.html.twig', [
            'Genre' => $form->createView()
        ]);
    }

    /**
     * @Route("/Genre/update/{id}", name="Genre_update", requirements={"id":"\d+"})
     */
    public function update(Genre $genre, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(UpdateGenreType::class,$genre);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($genre);
            $em->flush();

            $this->addFlash('success', 'Genre successfully updated!');
            return $this->redirectToRoute('Genre_list');
        }


        return $this->render('Genre/update.html.twig', [
            'GenreForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/Genre/del", name="Genre_del_default", defaults={"id":0})
     * @Route("/Genre/del/{id}", name="Genre_del")
     */
    public function del(EntityManagerInterface $em,$id)
    {
        $genre = $em->find(Genre::class,$id);
        if (!$genre) {
            throw $this->createNotFoundException('Aucun fichier en base a cet id');
        }else{
            $em->persist($genre);
            $em->flush();
        }
        return $this->redirectToRoute("Genre_list");
    }
}
