<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FormationController extends Controller
{
	// onglet actif
    protected $activeTab = 'formations';

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

            if($formation->getFormateur() != null)
            {
                $formateur = $this
                    ->getDoctrine()
                    ->getRepository('CentreFormationBundle:Formateur')
                    ->find($formation->getFormateur());

                $this->sendEmail($formation, $formateur);
            }

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

        $formateur = $formation->getFormateur();

        $form = $this->formCreate($formation, 'Modifier');

        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            if($formation->getFormateur() != null && $formation->getFormateur() != $formateur)
            {
                $formateur = $this
                    ->getDoctrine()
                    ->getRepository('CentreFormationBundle:Formateur')
                    ->find($formation->getFormateur());

                $this->sendEmail($formation, $formateur);
            }

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
        $years = array();
        $currentYear = intval(date('Y'));
        for($i=0; $i<6; $i++)
        {
            $years[] = $currentYear+$i;
        }

        return $form = $this->createFormBuilder($formation)
            ->add('libelle', 'text', array( 'label' => 'Libellé' ))
            ->add('date', 'date', array('placeholder' => array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'), 'years' => $years))
            ->add('duree', 'text', array( 'label' => 'Durée', 'attr' => array('min' => 1, 'max' => 24)))
            ->add('formateur', 'entity', array('class' => 'CentreFormationBundle:Formateur', 'required' => false, 'placeholder' => 'Aucun', 'empty_data'  => null))
            ->add($buttonLabel, 'submit', array( 'label' => $buttonLabel))
            ->getForm();
    }

    //Envoi d'un email si une formation est associé à un nouveau formateur
    protected function sendEmail($formation, $formateur)
    {
        $message = \Swift_Message::newInstance()
        ->setSubject('Une nouvelle formation vous a été attribuée')
        ->setFrom('info@centreformations.com')
        ->setTo($formateur->getEmail())
        ->setBody($this->renderView('CentreFormationBundle:emails:newFormation.html.twig', compact('formation', 'formateur')),'text/html');

        $this->get('mailer')->send($message);
    }
}