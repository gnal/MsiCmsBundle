<?php

namespace Msi\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageListener implements EventSubscriber
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(EventArgs $e)
    {
        $metadata = $e->getClassMetadata();

        if ($metadata->name !== $this->container->getParameter('msi_cms.page.class')) {
            return;
        }

        if (!$metadata->hasAssociation('site')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'site',
                'targetEntity' => $this->container->getParameter('msi_cms.site.class'),
                'joinColumns' => [
                    [
                        'onDelete' => 'CASCADE',
                    ],
                ],
            ]);
        }

        if (!$metadata->hasAssociation('parent')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'parent',
                'targetEntity' => $this->container->getParameter('msi_cms.page.class'),
                'joinColumns' => [
                    [
                        'onDelete' => 'SET NULL',
                    ],
                ],
                'inversedBy' => 'children',
            ]);
        }

        if (!$metadata->hasAssociation('children')) {
            $metadata->mapOneToMany([
                'fieldName'    => 'children',
                'targetEntity' => $this->container->getParameter('msi_cms.page.class'),
                'mappedBy' => 'parent',
            ]);
        }

        if (!$metadata->hasAssociation('blocks')) {
            $metadata->mapManyToMany([
                'fieldName'    => 'blocks',
                'targetEntity' => $this->container->getParameter('msi_cms.block.class'),
                'mappedBy' => 'pages',
            ]);
        }
    }
}
