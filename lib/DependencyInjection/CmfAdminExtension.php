<?php

namespace Symfony\Cmf\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

class CmfAdminExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        // TODO: Handle this in a better way? (or make it an explicit config option).
        if (class_exists(DocumentManagerInterface::class)) {
            $loader->load('doctrine-phpcr-odm/form.xml');
        }
    }
}
