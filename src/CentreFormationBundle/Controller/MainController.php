<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends Controller
{
	protected $prix = 90;

    public function accueilAction()
    {
        // onglet actif
        $activeTab = 'accueil';

        return $this->render('CentreFormationBundle:accueil:accueil.html.twig', compact('activeTab'));
    }

    public function remunerationsAction()
    {
    	$remunerations = $this->getDoctrine()
    						  ->getRepository('CentreFormationBundle:Formateur')
    						  ->getRemunerationList($this->prix);

    	// onglet actif
    	$activeTab = 'remunerations';

    	return $this->render('CentreFormationBundle:remunerations:show.html.twig', compact('remunerations', 'activeTab'));
    }
}
