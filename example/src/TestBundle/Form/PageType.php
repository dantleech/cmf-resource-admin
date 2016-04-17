<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build your custom form!
        $builder->add('title', 'text');
        $builder->add('content', 'text');
        $builder->add('published', 'checkbox');
        $builder->add('parent', 'phpcrodm_parent_path');
    }

    public function getName()
    {
        return 'acme_page';
    }
}
