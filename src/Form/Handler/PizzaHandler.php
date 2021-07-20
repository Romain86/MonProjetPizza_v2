<?php

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class PizzaHandler
{
    protected $form;
    protected $request;
    protected $em;

    /**
     * __construct
     *
     * @param  Form $form
     * @param  Request $request
     * @param  EntityManagerInterface $em
     * @return void
     */
    public function __construct(Form $form, Request $request, EntityManagerInterface $em)
    {
        $this->$form = $form;
        $this->$request = $request;
        $this->em = $em;
    }


    /**
     * process
     *
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->onSuccess();
            return true;
        }

        return false;
    }


    /**
     * getForm
     *
     * @return Form $form
     */
    public function getForm()
    {
        return $this->form;
    }


    /**
     * onSuccess
     *
     * @return void
     */
    protected function onSuccess()
    {
        $this->entityManager->persist($this->form->getData());
        $this->entityManager->flush();
    }
}