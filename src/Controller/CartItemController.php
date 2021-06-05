<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Form\CartItemType;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart/item")
 */
class CartItemController extends AbstractController
{

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

    /**
     * @Route("/delete/{id}", name="cart_item_delete", methods={"GET"})
     */
    public function delete(Product $product,CartItemRepository $cartItemRepository,CartRepository $cartRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user=$this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $cart=$user->getCart();
        $cartItem=$cartItemRepository->findOneBy(['cart'=> $cart,'product'=>$product]);
        if($cartItem->getQuantity()==1){
            $cartItem->getCart()->setTotalPrice($cartItem->getCart()->getTotalPrice() - $product->getPrice());
            $cart->removeCartItem($cartItem);
            $entityManager->remove($cartItem);
        }else{
            $cartItem->setQuantity($cartItem->getQuantity() - 1);
            $cartItem->setItemPrice($cartItem->getItemPrice() - $product->getPrice());
            $cartItem->getCart()->setTotalPrice($cartItem->getCart()->getTotalPrice() - $product->getPrice());
        }

        if ($cartRepository->findOneBy(['user' => $this->getUser()])->getTotalPrice()==0){
            $entityManager->remove($cart);
        }


        $entityManager->persist($cartItem);
        $entityManager->persist($cart);
        $entityManager->flush();

        return $this->redirectToRoute('cart');
    }
}
