<?php

namespace Msi\CmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MsiCmsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('admin.yml');

        $this->registerConfiguration($config, $container);
    }

    private function registerConfiguration($config, ContainerBuilder $container)
    {
        $container->setParameter('msi_cms.multisite', $config['multisite']);
        $container->setParameter('msi_cms.app_locales', $config['app_locales']);
        $container->setParameter('msi_cms.site.class', $config['site_class']);
        $container->setParameter('msi_cms.menu.class', $config['menu_class']);
        $container->setParameter('msi_cms.page.class', $config['page_class']);
        $container->setParameter('msi_cms.page.layouts', $config['page_layouts']);
        $container->setParameter('msi_cms.block.class', $config['block_class']);
        $container->setParameter('msi_cms.block.actions', $config['block_actions']);
        $container->setParameter('msi_cms.block.templates', $config['block_templates']);
        $container->setParameter('msi_cms.block.slots', $config['block_slots']);

        $container->setParameter('msi_cms.site.admin', $config['site_admin']);
        $container->setParameter('msi_cms.menu_root.admin', $config['menu_root_admin']);
        $container->setParameter('msi_cms.menu_node.admin', $config['menu_node_admin']);
        $container->setParameter('msi_cms.page.admin', $config['page_admin']);
        $container->setParameter('msi_cms.block.admin', $config['block_admin']);
    }
}
