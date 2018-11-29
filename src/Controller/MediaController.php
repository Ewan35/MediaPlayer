<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\AddMediaType;
use App\Form\UpdateMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends Controller
{
    /**
     * @Route("/media/list", name="media_list")
     */
    public function list(EntityManagerInterface $em)
    {
        $medias = $em->getRepository(Media::class)->findAllFull();

        return $this->render('media/list.html.twig', [
            'medias' => $medias,
        ]);
    }

    /**
     * @Route("/media/add", name="media_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $medias = new Media();

        $form = $this->createForm(AddMediaType::class,$medias);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($medias);
            $em->flush();

            $this->addFlash('success', 'Media successfully saved!');
            return $this->redirectToRoute('media_list');
        }

        return $this->render('media/add.html.twig', [
            'mediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/media/update/{id}", name="media_update", requirements={"id":"\d+"})
     */
    public function update(Media $media, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UpdateMediaType::class,$media);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media successfully updated!');
            return $this->redirectToRoute('media_list');
        }

        return $this->render('media/update.html.twig', [
            'mediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/media/del", name="media_del_default", defaults={"id":0})
     * @Route("/media/del/{id}", name="media_del")
     */
    public function del(EntityManagerInterface $em,$id)
    {
        $media = $em->find(Media::class,$id);
        if (!$media) {
            throw $this->createNotFoundException('Aucun fichier en base a cet id');
        }else{
            $media->setIsPublished(false);
            $em->persist($media);
            $em->flush();
        }
        return $this->redirectToRoute("media_list");
    }
}
