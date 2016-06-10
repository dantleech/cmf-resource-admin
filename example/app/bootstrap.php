<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Debug\Debug;


$loader = require __DIR__ . '/../../vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
Debug::enable();
