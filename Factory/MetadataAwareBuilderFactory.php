<?php

namespace FSC\HateoasBundle\Factory;

use FSC\HateoasBundle\Metadata\Builder\RelationsMetadataBuilder;
use FSC\HateoasBundle\Model\Builder\MetadataAwareBuilder;

class MetadataAwareBuilderFactory
{
    public function createBuilder()
    {
        $relationsBuilder = new RelationsMetadataBuilder();
        $builder = new MetadataAwareBuilder($relationsBuilder);

        return $builder;
    }
}
