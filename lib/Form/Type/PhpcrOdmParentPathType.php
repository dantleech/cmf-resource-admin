<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Cmf\Bundle\AdminBundle\Form\Transformer\PathToDocumentTransformer;

class PhpcrOdmParentPathType extends AbstractType
{
    private $manager;

    public function __construct(DocumentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new PathToDocumentTransformer($this->manager));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'phpcrodm_parent_path';
    }
}
