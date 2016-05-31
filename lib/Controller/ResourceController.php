<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Controller;

use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceController
{
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function dashboardAction(Request $request)
    {
        return $this->templating->renderResponse('CmfAdminBundle:Resource:dashboard.html.twig');
    }
}
