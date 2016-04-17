<?php

namespace Symfony\Cmf\Bundle\AdminBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\ODM\PHPCR\DocumentManagerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PathToDocumentTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(DocumentManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($document)
    {
        if (null === $document) {
            return '';
        }

        return $this->manager->getNodeForDocument($document)->getPath();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($path)
    {
        $document = $this->manager->find(null, $path);

        if (null === $document) {
            throw new TransformationFailedException(sprintf(
                'Could not find document for path "%s"',
                $path
            ));
        }

        return $document;
    }
}
