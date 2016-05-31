<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class CompositeBuilder
{
    private $factory;
    private $items;
    private $provider;

    public function __construct(
        FactoryInterface $factory,
        MenuProviderInterface $provider,
        array $items
    )
    {
        $this->factory = $factory;
        $this->provider = $provider;
        $this->items = $items;
    }

    public function build(array $options)
    {
        $node = $this->factory->createItem('root');
        $this->processItems($node, $this->items);

        return $node;
    }

    private function processItems($parentNode, $items)
    {
        foreach ($items as $itemName => $item) {
            $type = isset($item['type']) ? $item['type'] : 'item';

            if ($type === 'reference') {
                $parentNode->addChild($this->provider->get($itemName));
                continue;
            }

            if ($type === 'item') {
                $parentNode->addChild(
                    $itemName,
                    $item
                );
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'Unknown type "%s", must be either "item" or "service"',
                $type
            ));
        }
    }
}
