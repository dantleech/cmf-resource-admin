<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Menu;

use Sylius\Component\Resource\Metadata\Registry;
use Knp\Menu\FactoryInterface;
use Sylius\Component\Resource\Metadata\Metadata;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceBuilder
{
    private $factory;
    private $registry;

    public function __construct(
        FactoryInterface $factory,
        Registry $registry
    )
    {
        $this->factory = $factory;
        $this->registry = $registry;
    }

    public function buildMain(array $options)
    {
        $defaultRoute = null;

        // use the first route as the default for the main "Resources" item
        if (null === $defaultRoute) {
            foreach ($this->registry->getAll() as $metadata) {
                $defaultRoute = $this->getRouteName($metadata, 'index');
                break;
            }
        }

        $menu = $this->factory->createItem(
            'Resources',
            [
                'route' => 'cmf_admin_resource_dashboard'
            ]
        );

        foreach ($this->registry->getAll() as $metadata) {
            $key = ucfirst(Inflector::pluralize($metadata->getHumanizedName()));

            $menu->addChild(
                $key,
                [
                    // TODO: How to get the route name?
                    'route' => $this->getRouteName($metadata, 'index')
                ]
            );
        }

        return $menu;
    }

    private function getRouteName(Metadata $metadata, $type)
    {
        return sprintf(
            '%s_%s_%s',
            $metadata->getApplicationName(),
            $metadata->getName(),
            $type
        );
    }
}
