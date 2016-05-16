<?php

namespace CCH\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($reference)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product')
        ;

        if ($reference == null){
            $products = $repository->findAll();
            $res = array();
            foreach($products as $product){
                $prod = array();
                $prod['reference'] = $product->getReference();
                $prod['quantity'] = $product->getQuantity();
                $prod['prix'] = $product->getPriceWithoutTaxes();
                $res[]=$prod;
            }
        } else {
            // On récupère l'entité correspondante à l'id $id
            $products = $repository->findBy(array('reference' => $reference));
            $res = array();
            if ($products) {
                $product = $products[0];
                $res['reference'] = $product->getReference();
                $res['quantity'] = $product->getQuantity();
                $res['prix'] = $product->getPriceWithoutTaxes();
            }
        }
        $test = json_encode($res);
        print_r($test);
        die();
    }
}
