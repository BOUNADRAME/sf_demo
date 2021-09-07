<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{

    /**
     * Permet d'afficher une route paramétrée
     * @Route("/hello/{prenom}/age/{age}", name="hello")
     * @Route("/hello", name="hello_base")
     */
    public function hello($prenom = "devops", $age = 0){
        return $this->render(
            "hello.html.twig",
            [
                'prenom' => $prenom,
                'age' => $age,
            ]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        // return new Response("Bonjour tout le monde");
        return $this->render('home.html.twig', ['title'=> 'Tuto ANSD', 'age' => 17]);
    }
}

