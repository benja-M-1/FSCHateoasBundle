<?php

namespace FSC\HateoasBundle\Model;

use FSC\HateoasBundle\Metadata\ClassMetadataInterface;

class MetadataAware implements ClassMetadataInterface
{
    private $data;
    private $relations;

    public function __construct($data, array $relations)
    {
        $this->data = $data;
        $this->relations = $relations;
    }

    /**
     * {@inheritdoc}
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
