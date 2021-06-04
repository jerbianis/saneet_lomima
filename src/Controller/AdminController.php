<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route ("/admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);

    }

    /**
     * @Route("/products")
     */
    public function products(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->forward('App\Controller\ProductController::index');
    }


}
