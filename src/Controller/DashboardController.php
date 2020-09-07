<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();//obtengo usuario logueado
        if($user){
            //traje todos los post o entradas
        $post = $em->getRepository(Post::class)->BuscarAllPost();
        return $this->render('dashboard/index.html.twig', [
            'entradas' => $post
        ]);    
        }else{
            return $this->redirectToRoute('app_login');
        }
        
    }
}
