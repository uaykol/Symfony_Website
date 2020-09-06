<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('home/index.html.twig');

    }

    /**
     * @Route("/custom/{name?}", name="custom")
     */
    public function custom(\Symfony\Component\HttpFoundation\Request $request)
    {

        $name = dump($request->get('name'));

        return $this->render('home/custom.html.twig', [
            'name' => $name
        ]);

    }

}
