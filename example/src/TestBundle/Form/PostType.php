<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build your custom form!
        $builder->add('title', 'text');
        $builder->add('content', 'textarea', [
            'attr' =>  [
                'rows' => 8,
            ]
        ]);

        $builder->add('published', 'checkbox');
    }

    public function getName()
    {
        return 'acme_post';
    }
}
