<?php

namespace Symfony\Cmf\Bundle\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;

class CmfAdminExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $processor = new Processor();
        $config = new Configuration();
        $config = $processor->processConfiguration($config, $configs);

        // set container parameters
        $container->setParameter('cmf_admin.default_layout_template', $config['default_layout_template']);

        // set the main menu items
        $container->setParameter('cmf_admin.menu.items', $config['menu']['items']);

        // TODO: Handle this in a better way? (or make it an explicit config option).
        //       .. maybe use factories
        if (interface_exists(DocumentManagerInterface::class)) {
            $loader->load('doctrine-phpcr-odm/form.xml');
        }

        $loader->load('twig.xml');
        $loader->load('menu.xml');
        $loader->load('controllers.xml');
    }
}
