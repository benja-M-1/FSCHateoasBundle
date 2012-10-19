<?php

namespace FSC\HateoasBundle\Metadata;

use Metadata\MetadataFactoryInterface as BaseMetadataFactoryInterface;

use FSC\HateoasBundle\Metadata\ClassMetadataInterface;

class MetadataFactory implements MetadataFactoryInterface
{
    protected $metadataFactory;

    public function __construct(BaseMetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($object)
    {
        if (!is_object($object)) {
            return;
        }

        if ($object instanceof ClassMetadataInterface) {
            return $object;
        }

        return $this->metadataFactory->getMetadataForClass(get_class($object));
    }
}
