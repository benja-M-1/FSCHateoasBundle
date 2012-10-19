<?php

namespace FSC\HateoasBundle\Model\Builder;

use FSC\HateoasBundle\Metadata\Builder\RelationsMetadataBuilderInterface;
use FSC\HateoasBundle\Model\MetadataAware;

use Pagerfanta\PagerfantaInterface;

class MetadataAwareBuilder
{
    private $data;
    private $relationsMetadataBuilder;

    function __construct($data, RelationsMetadataBuilderInterface $relationsMetadataBuilder)
    {
        $this->data = $data;
        $this->relationsMetadataBuilder = $relationsMetadataBuilder;
    }

    public function addRelation($rel, array $href, array $embed = null)
    {
        $this->relationsMetadataBuilder->add($rel, $href, $embed);

        return $this;
    }

    public function addPagerfantaRelations(PagerfantaInterface $pager, $route, array $routeParameters = array(), $pageParameterName = 'page', $limitParameterName = 'limit')
    {
        if (!isset($routeParameters[$pageParameterName])) {
            $routeParameters[$pageParameterName] = $pager->getCurrentPage();
        }
        if (!isset($routeParameters[$limitParameterName])) {
            $routeParameters[$limitParameterName] = $pager->getMaxPerPage();
        }

        $this
            ->addRelation('self', array(
                'route' => $route,
                'parameters' => $routeParameters,
            ))
            ->addRelation('first', array(
                'route' => $route,
                'parameters' => array_merge($routeParameters, array($pageParameterName => '1'))
            ))
            ->addRelation('last', array(
                'route' => $route,
                'parameters' => array_merge($routeParameters, array($pageParameterName => $pager->getNbPages()))
            ))
        ;

        if ($pager->hasPreviousPage()) {
            $this
                ->addRelation('previous', array(
                    'route' => $route,
                    'parameters' => array_merge($routeParameters, array($pageParameterName => $pager->getPreviousPage())),
                ))
            ;
        }

        if ($pager->hasNextPage()) {
            $this
                ->addRelation('next', array(
                    'route' => $route,
                    'parameters' => array_merge($routeParameters, array($pageParameterName => $pager->getNextPage())),
                ))
            ;
        }
    }

    public function build()
    {
        return new MetadataAware($this->data, $this->relationsMetadataBuilder->build());
    }
}
