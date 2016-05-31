<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Twig;

use Twig_Extension_GlobalsInterface;
use Twig_Extension;
use Knp\Menu\Twig\Helper;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\ItemInterface;

class CmfAdminExtension extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    private $layoutTemplate;
    private $menuHelper;
    private $matcher;

    public function __construct(
        $layoutTemplate,
        Helper $helper,
        MatcherInterface $matcher
    )
    {
        $this->layoutTemplate = $layoutTemplate;
        $this->menuHelper = $helper;
        $this->matcher = $matcher;
    }


    public function getGlobals()
    {
        return [
            'cmf_admin_layout_template' => $this->layoutTemplate
        ];
    }

    public function getName()
    {
        return 'cmf_admin';
    }

    public function getFunctions()
    {
        return array(
            'cmf_admin_menu_tiers' => new \Twig_Function_Method($this, 'getMenuTiers'),
        );
    }

    /**
     * Returns the siblings sets leading from the root children to the set
     * containing the current menu item and then its children.
     *
     * TODO: Use a KNP Menu Renderer instead.
     *
     * @param \Knp\Menu\ItemInterface|string $menu
     * @return array
     */
    public function getMenuTiers($menu)
    {
        $menu = $this->menuHelper->get($menu);
        $current = $this->getCurrentItem($menu);

        if (!$current) {
            return array($menu->getChildren());
        }

        $tiers = array($current->getChildren());

        while ($current = $current->getParent()) {
            $tiers[] = $current->getChildren();
        }

        return array_reverse($tiers);
    }

    /**
     * Retrieves the current item.
     *
     * @param ItemInterface|string $menu
     *
     * @return ItemInterface
     */
    public function getCurrentItem($menu)
    {
        $rootItem = $this->menuHelper->get($menu);
        $currentItem = $this->retrieveCurrentItem($rootItem);

        if (null === $currentItem) {
            $currentItem = $rootItem;
        }

        return $currentItem;
    }

    /**
     * @param ItemInterface $item
     *
     * @return ItemInterface|null
     */
    private function retrieveCurrentItem(ItemInterface $item)
    {
        $currentItem = null;
        if ($this->matcher->isCurrent($item)) {
            $item->setAttribute('current', true);
            
            return $item;
        }

        if ($this->matcher->isAncestor($item)) {
            $item->setAttribute('current-ancestor', true);
            foreach ($item->getChildren() as $child) {
                $currentItem = $this->retrieveCurrentItem($child);
                if (null !== $currentItem) {
                    break;
                }
            }
        }
        return $currentItem;
    }
}
