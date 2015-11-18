<?php

namespace CentreFormationBundle\Controller;

use CentreFormationBundle\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MainController extends Controller
{
    public function accueilAction()
    {
        // onglet actif
        $activeTab = 'accueil';

        return $this->render('CentreFormationBundle:main:accueil.html.twig', compact('activeTab'));
    }
}
