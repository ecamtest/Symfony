<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CentreFormationBundle:Show:accueil.html.twig');
    }

    public function formateursAction()
    {
        $listeFormateurs = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->findAll();

    	return $this->render('CentreFormationBundle:Show:formateurs.html.twig', compact('listeFormateurs'));
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

            return $this->redirect($this->generateUrl('/'));
    	}

    	return $this->render('CentreFormationBundle:Add:formateur.html.twig', array('form' => $form->createView()));
    }
}
