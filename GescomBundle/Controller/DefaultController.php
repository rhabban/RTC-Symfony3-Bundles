<?php

namespace CCH\GescomBundle\Controller;

use CCH\GescomBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CCHGescomBundle:Default:index.html.twig');
    }
}
