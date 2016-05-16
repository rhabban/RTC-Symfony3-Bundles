<?php

namespace CCH\GescomBundle\Controller;

use CCH\GescomBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FloatType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function indexAction()
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product');

        // On récupère la tous les produits de la base de données
        $listProducts = $repository->findAll();

        // On envoie la liste des produits à la vue index.html.twig
        return $this->render('CCHGescomBundle:Product:index.html.twig', array(
            'listProducts' => $listProducts
        ));
    }

    public function viewAction($id)
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product');

        // On récupère l'entité correspondante à l'id $id
        $product = $repository->find($id);

        // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id  n'existe pas, d'où ce if :
        if (null === $product) {
            throw new NotFoundHttpException("Le produit d'id " . $id . " n'existe pas.");
        }

        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('CCHGescomBundle:Product:view.html.twig', array(
            'product' => $product
        ));
    }

    public function addAction(Request $request)
    {
        // Création de l'entité
        $product = new Product();

        $product->setCreationDate(new \DateTime());
        $product->setUpdateDate(new \DateTime());
        $product->setQuantity(0);
        $product->setPriceWithoutTaxes(0);


        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('reference', TextType::class, array(
                'label' => 'Référence',
            ))
            ->add('quantity', IntegerType::class, array(
                'label' => 'Quantité'
            ))
            ->add('priceWithoutTaxes', MoneyType::class, array(
                'currency' => 'EUR',
                'label' => 'Prix de vente HT'
            ))
            ->add('tva_percent', PercentType::class, array(
                'label' => 'TVA (%)'
            ))
            ->add('save', SubmitType::class, array('label' => 'Créer le produit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $em->flush();
            return $this->redirectToRoute('cch_gescom_product_index');
        }

        return $this->render('CCHGescomBundle:Product:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product');

        // On récupère l'entité correspondante à l'id $id
        $product = $repository->find($id);

        $product->setUpdateDate(new \DateTime());

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, array(
                'label' => 'Nom',
            ))
            ->add('reference', TextType::class, array(
                'label' => 'Référence',
            ))
            ->add('quantity', IntegerType::class, array(
                'label' => 'Quantité',
                'required' => 'false',
            ))
            ->add('priceWithoutTaxes', MoneyType::class, array(
                'currency' => 'EUR',
                'label' => 'Prix de vente HT',
                'required' => 'false',
            ))
            ->add('tva_percent', PercentType::class, array(
                'label' => 'TVA (%)'
            ))
            ->add('save', SubmitType::class, array('label' => 'Modifier le produit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $em->flush();
            return $this->redirectToRoute('cch_gescom_product_index');
        }

        return $this->render('CCHGescomBundle:Product:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // On récupère le repository
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product');

        // On récupère l'entité correspondante à l'id $id
        $product = $repository->find($id);

        if (null === $product) {
            throw new NotFoundHttpException("Le produit d'id " . $id . " n'existe pas.");
        }

        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('cch_gescom_product_index');
    }

    public function menuAction()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CCHGescomBundle:Product');

        $listProducts = $repository->findAll();

        return $this->render('CCHGescomBundle:Product:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listProducts' => $listProducts
        ));
    }
}
