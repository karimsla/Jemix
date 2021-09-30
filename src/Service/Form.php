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


        $typenamespace="Symfony\\Component\\Form\\Extension\\Core\\Type\\";

        $form=$this->formFactory->createBuilder(FormType::class,$entity);



        foreach ($fields as $field){
            $form->add($field['field'],$typenamespace.ucfirst($field['type']).'Type',["label"=>$field['label'],"attr"=>['value'=>$field["data"]]]);
            // $form->get($field['field'])->setData($field["data"]);
        }
        if($entity->getId()!=null){

            $form->add("id",HiddenType::class,array("attr"=>["value"=>$entity->getId()]));
            $form->add("Edit",SubmitType::class);
        }else{
            $form->add("Add",SubmitType::class);
        }

        return $form->getForm();
    }
}