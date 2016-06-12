<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Cmf\Admin\CmfAdminBundle;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Document\Page;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form\PageType;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Document\Post;
use Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form\PostType;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            // Symfony fundamentals
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),

            // Doctrine base and PHPCR
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\PHPCRBundle\DoctrinePHPCRBundle(),

            // Sylius Resource and dependencies
            new \FOS\RestBundle\FOSRestBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle($this),
            new \Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new \Sylius\Bundle\UiBundle\SyliusUiBundle(),
            new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),

            new Sylius\Bundle\GridBundle\SyliusGridBundle(),

            // Testing bundle
            new \Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\TestBundle(),

            // Admin Bundle
            new \Symfony\Cmf\Bundle\AdminBundle\CmfAdminBundle(),

            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new \winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
        ];
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(<<<'EOT'
alias: acme.page
grid: acme_page
redirect: update
templates: CmfAdminBundle:Crud
except: [ 'show ']
form: \Symfony\Cmf\Bundle\AdminBundle\Example\TestBundle\Form\PageType
EOT
        , '/admin', 'sylius.resource');

        $routes->import(<<<'EOT'
alias: acme.post
grid: acme_post
templates: CmfAdminBundle:Crud
redirect: update
except: [ 'show ']
EOT
        , '/admin', 'sylius.resource');

        $routes->import(
            '@WebProfilerBundle/Resources/config/routing/profiler.xml',
            '/_profiler'
        );
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->loadFromExtension('framework', [
            'session' => [],
            'translator' => [
                'fallback' => 'en',
            ],
            'secret' => '12345',
            'templating' => [
                'engines' => 'twig',
            ],
            'form' => [
                'enabled' => true,
            ],
            'csrf_protection' => true,
        ]);

        $container->loadFromExtension('security', [
            'providers' => [
                'in_memory' => [
                    'memory' => null,
                ],
            ],
            'firewalls' => [
                'main' => [
                    'anonymous' => null,
                ],
            ],
        ]);

        $container->loadFromExtension('twig', [
            'debug' => true,
            'strict_variables' => true,
            'form' => [
                'resources' => [
                    'bootstrap_3_layout.html.twig'
                ],
            ],
        ]);

        $container->loadFromExtension('web_profiler', [
            'toolbar' => true
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
            'drivers' => [ 'doctrine/phpcr-odm' ],
            'resources' => [
                'acme.post' => [
                    'driver' => 'doctrine/phpcr-odm',
                    'classes' => [
                        'model' => Post::class,
                    ],
                    'options' => [
                        'parent_path_default' => '/cms/posts',
                        'parent_path_autocreate' => true,
                    ],
                ],
                'acme.page' => [
                    'driver' => 'doctrine/phpcr-odm',
                    'classes' => [
                        'model' => Page::class,
                    ],
                    'options' => [
                        'parent_path_default' => '/cms/pages',
                        'parent_path_autocreate' => true,
                    ],
                ],
            ],
        ]);
        
        $container->loadFromExtension('cmf_admin', [
            'menu' => [
                'items' => [
                    'resource' => [
                        'type' => 'reference',
                    ],
                ],
            ],
        ]);

        $container->loadFromExtension('sylius_grid', [
            'drivers' => [ 'doctrine/phpcr-odm' ],
            'templates' => [
                'action' => [
                    'update' => 'CmfAdminBundle:Link:_update.html.twig',
                    'delete' => 'CmfAdminBundle:Link:_delete.html.twig',
                    'create' => 'CmfAdminBundle:Link:_create.html.twig',
                ],
                'filter' => [
                    'string' => 'CmfAdminBundle:Grid:Filter/string.html.twig',
                    'boolean' => 'CmfAdminBundle:Grid:Filter/boolean.html.twig',
                ],
            ],
            'grids' => [
                'acme_post' => [
                    'driver' => [
                        'name' => 'doctrine/phpcr-odm',
                        'options' => [
                            'class' => Post::class
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
                'acme_page' => [
                    'sorting' => [ 'title' => 'desc' ],
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
                    'filters' => [
                        'title' => [
                            'type' => 'string',
                        ],
                        'published' => [
                            'type' => 'boolean',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
