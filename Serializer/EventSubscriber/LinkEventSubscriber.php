<?php

namespace FSC\HateoasBundle\Serializer\EventSubscriber;

use JMS\SerializerBundle\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\SerializerBundle\Serializer\EventDispatcher\Events;
use JMS\SerializerBundle\Serializer\EventDispatcher\Event;
use JMS\SerializerBundle\Serializer\TypeParser;

use FSC\HateoasBundle\Factory\LinkFactoryInterface;
use FSC\HateoasBundle\Serializer\LinkSerializationHelper;

class LinkEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $methods = array();
        foreach (array('json', 'xml', 'yml') as $format) {
            $methods[] = array(
                'event' => Events::POST_SERIALIZE,
                'format' => $format,
                'method' => 'onPostSerialize'.('xml' == $format ? 'xml' : ''),
            );
        }

        return $methods;
    }

    protected $linkFactory;
    protected $linkSerializationHelper;

    public function __construct(LinkFactoryInterface $linkFactory, LinkSerializationHelper $linkSerializationHelper)
    {
        $this->linkFactory = $linkFactory;
        $this->linkSerializationHelper = $linkSerializationHelper;
    }

    public function onPostSerializeXML(Event $event)
    {
        if (null === ($links = $this->linkFactory->createLinks($event->getObject(), $event->getType()))) {
            return;
        }

        $this->linkSerializationHelper->addLinksToXMLSerialization($links, $event->getVisitor());
    }

    public function onPostSerialize(Event $event)
    {
        if (null === ($links = $this->linkFactory->createLinks($event->getObject()))) {
            return;
        }

        $visitor = $event->getVisitor();
        $visitor->addData('links', $this->linkSerializationHelper->createGenericLinksData($links, $visitor));
    }
}
