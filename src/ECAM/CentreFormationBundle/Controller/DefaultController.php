<?php

namespace ECAM\CentreFormationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ECAMCentreFormationBundle:Default:index.html.twig', array('name' => $name));
    }
}
