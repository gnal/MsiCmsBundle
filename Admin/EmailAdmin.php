<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class EmailAdmin extends Admin
{
    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('published', 'boolean', [
                'label' => 'Enabled',
            ])
            ->add('name')
            ->add('subject')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
            ->add('fromWho')
            ->add('toWho')
            ->add('cc')
            ->add('bcc')
            ->add('availableVars', 'textarea', [
                'attr' => [
                    'data-help' => $this->container->get('translator')->trans('availablevars_data_help'),
                ],
            ])
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('subject')
            ->add('body', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                ],
            ])
        ;
    }
}
