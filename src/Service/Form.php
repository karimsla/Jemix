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


        //namespace of widget type
        $typenamespace="Symfony\\Component\\Form\\Extension\\Core\\Type\\";

        //create builder from the entity
        $form=$this->formFactory->createBuilder(FormType::class,$entity);



        foreach ($fields as $field){
            $form->add(
                $field['field'],//field name
                $typenamespace.ucfirst($field['type']).'Type',//widget type
                [
                    "label"=>$field['label'],//label
                    "attr"=>[
                        'value'=>$field["data"]//value
                    ]
                ]
            );

        }


        if($entity->getId()!=null){//id is present => edit

            $form->add("id",HiddenType::class,array("attr"=>["value"=>$entity->getId()]));
            $form->add("Edit",SubmitType::class);


        }else{

            $form->add("Add",SubmitType::class);

        }

        return $form->getForm();
    }
}