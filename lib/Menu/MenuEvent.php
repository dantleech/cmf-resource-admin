<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuEvent extends Event
{
    private $factory;
    private $menu;

    public function __construct(
        FactoryInterface $factory,
        ItemInterface $menu
    )
    {
        $this->factory = $factory;
        $this->menu = $menu;
    }

    public function getMenu()
    {
        return $this->menu;
    }

    public function getFactory()
    {
        return $this->factory;
    }
}
