<?php
/**
 * Created by PhpStorm.
 * User: epenanchoat2017
 * Date: 27/11/2018
 * Time: 14:24
 */

namespace App\Controller;


use App\Entity\TypeMedia;
use App\Form\AddTypeMediaType;
use App\Form\UpdateMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TypeMediaController extends Controller
{
    /**
     * @Route("/TypeMedia/list", name="TypeMedia_list")
     */
    public function list(EntityManagerInterface $em)
    {

        $typeMedias = $em->getRepository(TypeMedia::class)->findAllFull();

        return $this->render('TypeMedia/list.html.twig', [
            'typeMedias' => $typeMedias,
        ]);
    }


    /**
     * @Route("/TypeMedia/add", name="TypeMedia_add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $typeMedias = new TypeMedia();

        $form = $this->createForm(AddTypeMediaType::class,$typeMedias);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($typeMedias);
            $em->flush();

            $this->addFlash('success', 'Type media successfully saved!');
            return $this->redirectToRoute('TypeMedia_list');
        }

        return $this->render('TypeMedia/add.html.twig', [
            'TypeMediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/TypeMedia/update/{id}", name="TypeMedia_update", requirements={"id":"\d+"})
     */
    public function update(TypeMedia $typeMedia, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(UpdateMediaType::class,$typeMedia);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($typeMedia);
            $em->flush();

            $this->addFlash('success', 'Type media successfully updated!');
            return $this->redirectToRoute('TypeMedia_list');
        }


        return $this->render('TypeMedia/update.html.twig', [
            'TypeMediaForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/TypeMedia/del", name="TypeMedia_del_default", defaults={"id":0})
     * @Route("/TypeMedia/del/{id}", name="TypeMedia_del")
     */
    public function delete(EntityManagerInterface $em,TypeMedia $TypeMedia)
    {
        //vérification côté serveur
        if(count($TypeMedia->getIdeas()) > 0){
            $this->addFlash('error', "You can't delete this media !");
            return $this->redirectToRoute('TypeMedia_list');
        }

        $em->remove($TypeMedia);
        $em->flush();
        $this->addFlash("success", "Type media successfully deleted!");
        return $this->redirectToRoute("TypeMedia_list");
    }
}