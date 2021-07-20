<?php


namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Pizza;
use App\Form\Type\PizzaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/json/pizza",  name="pizzajson", methods="GET")
     */
    public function jsonpizza(EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->findAll();
        return $this->json(['pizzas' => $pizza]);
    }

    /**
     * @Route("/api/json/pizza/{id}", name="showjsonpizza", methods="GET", requirements={"id"="\d+"})
     */
    public function showjsonpizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);
        return $this->json(['pizzas' => $pizza]);
    }

    /**
     * @Route("/api/json/pizza", name="newpizzajson", methods="POST")
     */
    public function newjsonpizza(EntityManagerInterface $em, Request $request)
    {
        $pizza = new Pizza();
        $form = $this->createForm(PizzaType::class, $pizza);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pizza = $form->getData();
            $em->persist($pizza);
            $em->flush();

            return $this->redirectToRoute("Pizzajson");
        }
        return $this->render("form.html.twig", ['pizzaForm' => $form->createView()]);
    }

    /**
     * @Route("/api/json/pizza/{id}", name="updatejsonpizza", methods="PUT", requirements={"id"="\d+"})
     */
    public function updatejsonpizza($id, EntityManagerInterface $em, Request $request)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);
        $form = $this->createForm(PizzaType::class, $pizza);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pizza);
            $em->flush();
            return $this->redirectToRoute('pizzajson');
        }

        return $this->render('form.html.twig', ['pizzaForm' => $form->createView()]);
    }

    /**
     * @Route("api/json/pizza/{id}", name="deletejsonpizza", methods="DELETE", requirements={"id"="\d+"})
     */
    public function deletejsonpizza($id, EntityManagerInterface $em)
    {
        $pizza = $em->getRepository(Pizza::class)->find($id);
        $em->remove($pizza);
        $em->flush();
        return $this->redirectToRoute("pizzajson");
    }

    // \  /  |\  /|  |
    //  \/   | \/ |  |
    //  /\   |    |  |
    // /  \  |    |  |___

    

}
