<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CentreFormationBundle:Show:accueil.html.twig');
    }

    public function formateursAction()
    {
    	return $this->render('CentreFormationBundle:Show:formateurs.html.twig');
    }

    public function addFormateurAction(Request $request)
    {
    	$formateur = new Formateur();

    	$form = $this->createFormBuilder($formateur)
    		->add('nom', 'text')
    		->add('prenom', 'text')
    		->add('gsm', 'text')
    		->add('email', 'email')
    		->add('Ajouter', 'submit')
    		->getForm();

    	$form->handleRequest($request);
    	$formateur = $form->getData();

    	if ($form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($formateur);
    		$em->flush();
    	}

    	return $this->render('CentreFormationBundle:Add:formateur.html.twig', array('form' => $form->createView()));
    }
}
