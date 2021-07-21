<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pizza;
use App\Form\Type\PizzaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PizzaController extends AbstractController
{
    /**
     * @Route("/pizza", name="pizza", methods="GET")
     */
    public function pizza(EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->findAll();
        return $this->render('listePizza.html.twig', ['pizzas' => $pizza]);
    }

    /**
     * @Route("/pizza/{id}", name="showPizza", methods="GET", requirements={"id"="\d+"})
     */
    public function showPizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);

        return $this->render("pizza.html.twig", ['pizza' => $pizza]);
    }

    /**
     * @Route("/pizza/", name="newPizza", methods="POST")
     */
    public function newPizza(Request $request, EntityManagerInterface $em)
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pizza);
            $em->flush();
            $this->addFlash(
                'success',
                'Pizza ajoutée'
            );

            $lastPizza = $em->getRepository(Pizza::class)->findOneBy([], ['id' => 'desc']);
            $lastId = $lastPizza->getId();


            $uploaddir = "./../public/images/";
            $uploadfile = $uploaddir . basename($_FILES['pizza']['name']['File']);



            if (move_uploaded_file($_FILES['pizza']['tmp_name']['File'], $uploadfile)) {
                $this->addFlash(
                    'success',
                    'Image uploader'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Impossible d\'uploader l\'image'
                );
            }

            rename($uploadfile, $uploaddir . $lastId. '.jpg');

            return $this->redirectToRoute('pizza');
        }

        return $this->render('form.html.twig', ['pizzaForm' => $form->createView()]);
    }

    /**
     * @Route("/pizza/{id}", name="updatePizza", methods="PUT", requirements={"id"="\d+"})
     */
    public function updatePizza($id, EntityManagerInterface $em, Request $request)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);

        $form = $this->createForm(PizzaType::class, $pizza);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pizza);
            $em->flush();
            $this->addFlash(
                'success',
                'Pizza modifiée'
            );

            $uploaddir = "./../public/images/";
            $uploadfile = $uploaddir . basename($_FILES['pizza']['name']['File']);



            if (move_uploaded_file($_FILES['pizza']['tmp_name']['File'], $uploadfile)) {
                $this->addFlash(
                    'success',
                    'Image uploader'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'Impossible d\'uploader l\'image'
                );
            }

            rename($uploadfile, $uploaddir . $id . '.jpg');

            return $this->redirectToRoute('pizza');
        }

        return $this->render('form.html.twig', ['pizzaForm' => $form->createView()]);
    }


    /**
     * @Route("/pizza/{id}", name="deletePizza", methods="DELETE", requirements={"id"="\d+"})
     */
    public function deletePizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);
        if (empty($pizza)) {
            $this->addFlash(
                "warning",
                "Impossible de supprimer la pizza"
            );
        } else {
            $em->remove($pizza);
            $em->flush();
            $this->addFlash(
                "success",
                "La pizza a été supprimée"
            );
        }

        return $this->redirectToRoute('pizza');
    }
}
