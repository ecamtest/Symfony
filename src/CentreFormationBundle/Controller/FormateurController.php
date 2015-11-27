<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormateurController extends Controller
{
    // onglet actif
    protected $activeTab= 'formateurs';

    public function showAction()
    {
        $liste = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->findAll();

        $activeTab = $this->activeTab;
    	return $this->render('CentreFormationBundle:formateurs:show.html.twig', compact('liste', 'activeTab'));
    }

    public function addAction(Request $request)
    {
    	$formateur = new Formateur();

    	$form = $this->formCreate($formateur, 'Créer');

    	$form->handleRequest($request);
    	$formateur = $form->getData();

    	if ($form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($formateur);
    		$em->flush();

            return $this->redirect($this->generateUrl('_formateurs'));
    	}

        $formCreateView = $form->createView();

        $activeTab = $this->activeTab;
    	return $this->render('CentreFormationBundle:templates:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function editAction($id, Request $request)
    {
        $formateur = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->find($id);

        if($formateur == null)
        	return $this->redirect($this->generateUrl('_formateurs'));
        
        $form = $this->formCreate($formateur, 'Modifier');

        $form->handleRequest($request);
        $formateur = $form->getData();

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($formateur);
            $em->flush();

            return $this->redirect($this->generateUrl('_formateurs'));
        }

        $formCreateView = $form->createView(); 

        $activeTab = $this->activeTab;
        return $this->render('CentreFormationBundle:templates:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function deleteAction($id)
    {
        $formateur = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->find($id);

        $formations = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formation')
            ->findByFormateur($id);   

        $em = $this->getDoctrine()->getManager();

        foreach ($formations as $formation) {
        	$formation->setFormateur(null);
        }
        
        $em->remove($formateur);
        $em->flush();

        return $this->redirect($this->generateUrl('_formateurs'));
    }

    // Génération du formulaire formateur
    protected function formCreate($formateur, $buttonLabel)
    {
        return $form = $this->createFormBuilder($formateur)
            ->add('nom', 'text')
            ->add('prenom', 'text', array( 'label' => 'Prénom' ))
            ->add('gsm', 'text', array( 'label' => 'GSM' ))
            ->add('email', 'email')
            ->add($buttonLabel, 'submit', array( 'label' => $buttonLabel))
            ->getForm();
    }
}
