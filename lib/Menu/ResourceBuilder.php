<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Menu;

use Sylius\Component\Resource\Metadata\Registry;
use Knp\Menu\FactoryInterface;
use Sylius\Component\Resource\Metadata\Metadata;
use Doctrine\Common\Inflector\Inflector;

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
        $menu = $this->factory->createItem('root');

        foreach ($this->registry->getAll() as $metadata) {
            $key = ucfirst(Inflector::pluralize($metadata->getHumanizedName()));

            $menu->addChild(
                $key,
                [
                    // TODO: How to get the route name?
                    'route' => $this->getRouteName($metadata, 'index')
                ]
            );

            $menu[$key]->addChild(
                'List',
                [
                    'route' => $this->getRouteName($metadata, 'index'),
                ]
            );
            $menu[$key]->addChild(
                'Create', 
                [
                    'route' => $this->getRouteName($metadata, 'create'),
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
