<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormateurController extends Controller
{
    public function listeAction()
    {
        $liste = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->findAll();

        // onglet actif
        $activeTab = 'formateurs';

    	return $this->render('CentreFormationBundle:formateurs:liste.html.twig', compact('liste', 'activeTab'));
    }

    public function addAction(Request $request)
    {
    	$formateur = new Formateur();

    	$form = $this->createFormBuilder($formateur)
    		->add('nom', 'text')
    		->add('prenom', 'text', array( 'label' => 'Prénom' ))
    		->add('gsm', 'text', array( 'label' => 'GSM' ))
    		->add('email', 'email')
    		->add('Ajouter', 'submit')
    		->getForm();

    	$form->handleRequest($request);
    	$formateur = $form->getData();

    	if ($form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($formateur);
    		$em->flush();

            return $this->redirect($this->generateUrl('_formateurs'));
    	}

        $formCreateView = $form->createView();
        // onglet actif
        $activeTab = 'formateurs';

    	return $this->render('CentreFormationBundle:formateurs:create.html.twig', compact('formCreateView', 'activeTab'));
    }
}
