<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\CartItemType;
use App\Repository\CartItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart/item")
 */
class CartItemController extends AbstractController
{
    /**
     * @Route("/", name="cart_item_index", methods={"GET"})
     */
    public function index(CartItemRepository $cartItemRepository): Response
    {
        return $this->render('cart_item/index.html.twig', [
            'cart_items' => $cartItemRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="cart_item_new", methods={"GET"})
     */
    public function new(Product $product,CartItemRepository $cartItemRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user=$this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        if($cart=$user->getCart()){
            if ($cartItem=$cartItemRepository->findOneBy(['cart'=> $cart,'product'=>$product])){
                $cartItem->setQuantity($cartItem->getQuantity() + 1);
                $cartItem->setItemPrice($cartItem->getItemPrice() + $product->getPrice());
                $cartItem->getCart()->setTotalPrice($cartItem->getCart()->getTotalPrice() + $product->getPrice());
            }else{
                $cartItem= new CartItem();
                $cartItem->setCart($cart);
                $cartItem->setProduct($product);
                $cartItem->setQuantity(1);
                $cartItem->setItemPrice($product->getPrice());
                $cartItem->getCart()->setTotalPrice($cartItem->getCart()->getTotalPrice() + $product->getPrice());

            }

        }else{
            $cart=new Cart();
            $cartItem= new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $cartItem->setItemPrice($product->getPrice());
            $cart->setTotalPrice($product->getPrice());
            $cart->addCartItem($cartItem);
            $user->setCart($cart);


        }
        $entityManager->persist($cart);
        $entityManager->persist($cartItem);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
    
//    /**
//     * @Route("/{id}", name="cart_item_delete", methods={"POST"})
//     */
//    public function delete(Request $request, CartItem $cartItem): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($cartItem);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('cart_item_index');
//    }
}
