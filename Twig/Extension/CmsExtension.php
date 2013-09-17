<?php

namespace Msi\CmsBundle\Twig\Extension;

class CmsExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'msi_block_render' => new \Twig_Function_Method($this, 'renderBlock', array('is_safe' => array('html'))),
        );
    }

    public function getGlobals()
    {
        $globals = [];
        if (!$this->container->isScopeActive('request')) {
            return $globals;
        }
        $request = $this->container->get('request');

        $site = $this->container->get('msi_admin.provider')->getSite();
        $globals['site'] = $site;

        $page = $this->container->get('msi_cms.page_manager')->findByRoute($request->attributes->get('_route'));
        if (!$page) {
            $page = $this->container->get('msi_cms.page_manager')->findOrCreate($this->container->get('msi_admin.provider')->getWorkingLocale());
        }
        $globals['page'] = $page;

        return $globals;
    }

    public function renderBlock($slot, $page)
    {
        $content = '';
        foreach ($page->getBlocks() as $block) {
            if ($block->getRendered() === true) {
                continue;
            }
            if (!$block->getTranslation()->getPublished()) {
                continue;
            }
            if ($block->getSlot() !== $slot) {
                continue;
            }
            $handler = $this->container->get($block->getType());
            $content .= $handler->execute($block, $page);
            $block->setRendered(true);
        }

        return $content;
    }

    public function getName()
    {
        return 'msi_cms_cms';
    }
}
