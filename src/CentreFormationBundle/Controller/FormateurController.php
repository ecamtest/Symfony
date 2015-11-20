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
    	return $this->render('CentreFormationBundle:formateurs:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function editAction($id, Request $request)
    {
        $formateur = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->find($id);

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
        return $this->render('CentreFormationBundle:formateurs:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function deleteAction($id)
    {
        $formateur = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formateur')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($formateur);
        $em->flush();

        return $this->forward('CentreFormationBundle:Formateur:show');
    }

    // Génération du formulaire formateur
    protected function formCreate($formateur, $buttonType)
    {
        return $form = $this->createFormBuilder($formateur)
            ->add('nom', 'text')
            ->add('prenom', 'text', array( 'label' => 'Prénom' ))
            ->add('gsm', 'text', array( 'label' => 'GSM' ))
            ->add('email', 'email')
            ->add($buttonType, 'submit')
            ->getForm();
    }
}
