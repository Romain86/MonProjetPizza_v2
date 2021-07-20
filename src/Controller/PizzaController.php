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
     * @Route("/pizzas", name="pizzas", methods="GET")
     */
    public function pizzas(EntityManagerInterface $em)
    {
        $pizzas = $em->getRepository(Pizza::class)->findAll();
        return $this->render('listePizza.html.twig', ['pizzas' => $pizzas]);
    }

    /**
     * @Route("/pizzas/{id}", name="showPizza", methods="GET", requirements={"id"="\d+"})
     */
    public function showPizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);

        return $this->render("pizza.html.twig", ['pizza' => $pizza]);

    }

    /**
     * @Route("/pizzas/new", name="newPizza", methods="POST")
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

            return $this->redirectToRoute('pizzas');
        } 

        return $this->render('form.html.twig', ['pizzaForm' => $form->createView()]);
    }


    /**
     * @Route("/pizzas/{id}", name="updatePizza", methods="GET", requirements={"id"="\d+"})
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

            return $this->redirectToRoute('pizzas');
        } 

        return $this->render('form.html.twig', ['pizzaForm' => $form->createView()]);
    }


    /**
     * @Route("/pizzas/{id}", name="deletePizza", methods="DELETE", requirements={"id"="\d+"})
     */
    public function deletePizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);
        if(empty($pizza)){
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

        return $this->redirectToRoute('pizzas');
    }
}
