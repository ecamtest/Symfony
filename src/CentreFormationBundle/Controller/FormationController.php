<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormationController extends Controller
{
	// onglet actif
    protected $activeTab= 'formations';

    public function showAction()
    {
        $liste = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formation')
            ->findAll();

        $activeTab = $this->activeTab;
    	return $this->render('CentreFormationBundle:formations:show.html.twig', compact('liste', 'activeTab'));
    }

    public function addAction(Request $request)
    {
    	$formation = new Formation();

    	$form = $this->formCreate($formation, 'Créer');

    	$form->handleRequest($request);
    	$formation = $form->getData();

    	if ($form->isValid()){
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($formation);
    		$em->flush();

            return $this->redirect($this->generateUrl('_formations'));
    	}

        $formCreateView = $form->createView();

        $activeTab = $this->activeTab;
    	return $this->render('CentreFormationBundle:templates:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function editAction($id, Request $request)
    {
        $formation = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formation')
            ->find($id);

        if($formation == null)
            return $this->redirect($this->generateUrl('_formations'));

        $form = $this->formCreate($formation, 'Modifier');

        $form->handleRequest($request);
        $formateur = $form->getData();

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirect($this->generateUrl('_formations'));
        }

        $formCreateView = $form->createView(); 

        $activeTab = $this->activeTab;
        return $this->render('CentreFormationBundle:templates:form.html.twig', compact('formCreateView', 'activeTab'));
    }

    public function deleteAction($id)
    {
        $formation = $this
            ->getDoctrine()
            ->getRepository('CentreFormationBundle:Formation')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($formation);
        $em->flush();

        return $this->redirect($this->generateUrl('_formations'));
    }

    // Génération du formulaire formateur
    protected function formCreate($formation, $buttonLabel)
    {
        return $form = $this->createFormBuilder($formation)
            ->add('libelle', 'text', array( 'label' => 'Libellé' ))
            ->add('date', 'date')
            ->add('duree', 'number', array( 'label' => 'Durée', 'attr' => array('min' => 1, 'max' => 24)))
            ->add('formateur', 'entity', array('class' => 'CentreFormationBundle:Formateur'))
            ->add($buttonLabel, 'submit', array( 'label' => $buttonLabel))
            ->getForm();
    }
}