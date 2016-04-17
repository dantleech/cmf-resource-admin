<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Cmf\Admin\CmfAdminBundle;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Document\Page;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form\PageType;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            // Symfony fundamentals
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),

            // Doctrine base and PHPCR
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),

            // Sylius Resource and dependencies
            new \FOS\RestBundle\FOSRestBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle($this),
            new \Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),

            new Sylius\Bundle\GridBundle\SyliusGridBundle(),

            // Testing bundle
            new \Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\TestBundle(),

            // Admin Bundle
            new Symfony\Cmf\Bundle\AdminBundle\CmfAdminBundle(),
        ];
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(<<<'EOT'
alias: acme.page
grid: acme_page
except: [ 'show ']
EOT
        , '/admin', 'sylius.resource');
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->loadFromExtension('framework', [
            'session' => [],
            'secret' => '12345',
            'templating' => [
                'engines' => 'twig',
            ],
            'form' => [
                'enabled' => true,
            ],
            'csrf_protection' => true,
        ]);

        $container->loadFromExtension('doctrine_phpcr', [
            'session' => [
                'backend' => [
                    'type' => 'doctrinedbal',
                ],
                'workspace' => 'test',
                'username' => 'admin',
                'password' => 'admin',
            ],
            'odm' => [
                'auto_mapping' => true,
            ],
        ]);

        $container->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/cache/test.sqlite',
            ]
        ]);

        $container->loadFromExtension('sylius_resource', [
            'resources' => [
                'acme.page' => [
                    'driver' => 'doctrine/phpcr-odm',
                    'templates' => '@CmfAdmin/Crud',
                    'classes' => [
                        'model' => Page::class,
                        'form' => [
                            'default' => PageType::class,
                        ],
                    ],
                ],
            ],
        ]);

        $container->loadFromExtension('sylius_grid', [
            'templates' => [
                'action' => [
                    'update' => 'CmfAdminBundle:Link:_update.html.twig',
                    'delete' => 'CmfAdminBundle:Link:_delete.html.twig',
                    'create' => 'CmfAdminBundle:Link:_create.html.twig',
                ],
            ],
            'grids' => [
                'acme_page' => [
                    'driver' => [
                        'name' => 'doctrine/phpcr-odm',
                        'options' => [
                            'class' => Page::class
                        ]
                    ],
                    'fields' => [
                        'title' => [ 'type' => 'string' ],
                        'published' => [ 'type' => 'string' ],
                        'path' => [ 'type' => 'string' ],
                    ],
                    'actions' => [
                        'main' => [
                            'create' => [
                                'type' => 'create',
                            ],
                        ],
                        'item' => [
                            'update' => [
                                'type' => 'update',
                            ],
                            'delete' => [
                                'type' => 'delete',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
