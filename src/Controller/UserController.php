<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Form;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param  Request  $request
     * @Route("/User/List",name="user-list")
     */
    public function listUser(Request $request,EntityManagerInterface $em){
        $users=$em->getRepository(User::class)->findAll();
        return $this->render('user/list.html.twig',array("users"=>$users));


    }

    /**
     * @param  Request  $request
     * @param  EntityManagerInterface  $em
     * @param  Form  $formBuilder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/User/Add",name="user-add")
     */
    public function addUser(Request $request,EntityManagerInterface $em,Form $formBuilder){


        $user=$em->getRepository(User::class)->find(2) ;





        //manually will create the entity and the fields
        $fields=[
            [
                'field'=>'id',
                'label'=>'This is id',
                'type'=>'hidden',
                'data'=>$user->getId(),

            ],
            [
                'field'=>'email',
                'label'=>'This is email',
                'type'=>'email',
                'data'=>$user->getEmail(),

            ],
            [
                'field'=>'password',
                'label'=>'This is password',
                'type'=>'password',
                'data'=>null,
            ]

        ];







        //generate the form
        $form=$formBuilder->generateForm($user,$fields);
        $form->remove("roles");
        $form->handleRequest($request);


        //error cause role have type json



        if($form->isSubmitted() && $form->isValid()){
            if($user->getId()==null|| $user->getId()==0){
                $em->persist($user);

            }
            $em->flush();
            return $this->redirectToRoute("user-list");

        }
        $view=$form->createView();
        return $this->render("user/add.html.twig",array("form"=>$view));






    }




    /**
     * @Route("/generate",name="generate")
     */
    public function generateForm(Form $formbuilder,Request $request,EntityManagerInterface $em){

        $user=new User();

        $fields=[
            [
                'field'=>'email',
                'label'=>'This is email',
                'type'=>'email',
                'data'=>'k.sleimi@gmail.com',

            ],
            [
                'field'=>'password',
                'label'=>'This is password',
                'type'=>'password',
                'data'=>null,
            ]

        ];


        $form=$formbuilder->generateForm($user,$fields);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           if($user->getId()!=null|| $user->getId()==0){
               $em->persist($user);

           }
            $em->flush();
        }



        return $this->render("user/generate.html.twig",array("form"=>$form->createView()));


    }


    function accessProtected($name, $field) {
        $reflection = new ReflectionClass($name);
        $property = $reflection->getProperty($field);
        $property->setAccessible(true);
        return $property->getValue($name);
    }
}
