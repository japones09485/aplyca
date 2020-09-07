<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index( Request $request,UserPasswordEncoderInterface $passwordUsu )
    {   
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        //DETERMINO DI EL FORMULARIO FUE ENVIADO
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            //manejador de acciones en BD          
            $em = $this->getDoctrine()->getManager();
            $user->setBaneado(false);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($passwordUsu->encodePassword($user, $form['password']->getData()));
            $em->persist($user);
            $em->flush();
         
        $this->addFlash(
            'exito',
            $user :: REGISTRO_EXITOSO
        );
            return $this->redirectToRoute('register');
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'saludo' => 'Hola',
            'formulario' => $form->createView()
        ]);
    }
}
