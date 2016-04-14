<?php

namespace PsaNdp\ConfiguratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PsaNdpConfiguratorBundle:Default:index.html.twig', array('name' => $name));
    }
}
