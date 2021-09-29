<?php
namespace App\Service;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;


class Form  {


    private $formFactory;
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }


    public function generateForm($entity,$fields){

     //   $form=$this->builder->create()-> ($entity);
        $typenamespace="Symfony\\Component\\Form\\Extension\\Core\\Type\\";
        $form=$this->formFactory->createBuilder(FormType::class,$entity);
        if($entity->getId()!=null){

        $form->add("id",HiddenType::class,array("attr"=>["value"=>$entity->getId()]));
        }

        foreach ($fields as $field){
            $form->add($field['field'],$typenamespace.ucfirst($field['type']).'Type',["label"=>$field['label'],"attr"=>['value'=>$field["data"]]]);
            // $form->get($field['field'])->setData($field["data"]);
        }


        return $form->getForm();
    }
}