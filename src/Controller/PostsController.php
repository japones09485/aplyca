<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\UsuContacto;
use App\Form\PostType;
use Exception;
use Symfony\Bridge\Twig\Node\RenderBlockNode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostsController extends AbstractController
{
    
    /**
     * @Route("/registrar-entrada", name="RegistrarEntrada")
     */
    public function index(Request $request, SluggerInterface $slugger)
    {
        $post = new Post();
        $form = $this->createForm(PostType :: class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('fotos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('message', 'Ha ocurrido un error!!!');
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }

            $usu = $this->getUser();
            $post->setUsuario($usu);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('posts/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ver-entrada/{id}", name="VerEntrada")
     */
    
    public function VerPost($id){
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($id);
        return $this->render('posts/ver-entrada.html.twig',['post'=> $post]);
    }


    /**
     * @Route("/contacto", name="Contacto")
     */
    
    public function Contacto(Request $request){
        $cont = new UsuContacto();
        $form = $this->createForm(ContactoType :: class, $cont);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($cont);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('posts/index.html.twig', [
            'form' => $form->createView()
        ]);
    }




     /**
     * @Route("/mis-entradas", name="MisEntradas")
     */
    
    public function MisPost(){
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $id = $user->getId();
        $entradas = $em->getRepository(Post::class)->PostUsu($id);
        return $this->render('posts/mis-entradas.html.twig',['entradas'=> $entradas]);
    }
}
