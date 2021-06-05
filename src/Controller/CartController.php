<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(CartRepository $cartRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('cart/index.html.twig', [
            'cart' => $cartRepository->findOneBy(['user'=>$this->getUser()]),
        ]);
    }

    public function cartnav(CartRepository $cartRepository ):Response
    {
        if($cartRepository->findOneBy(['user'=>$this->getUser()])==null) $c=0;
        else $c = $cartRepository->findOneBy(['user' => $this->getUser()])->getCartItems()->count();
        return $this->render('cartitemcount.html.twig', [
            'cartitemcount'=>$c,
        ]);
    }
}
