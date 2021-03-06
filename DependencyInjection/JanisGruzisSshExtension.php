<?php

namespace JanisGruzis\SshBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class JanisGruzisSshExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

		$this->loadSshConnections($config, $container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

	/**
	 * Load SSH services from configuration into container.
	 * @param array $config
	 * @param ContainerBuilder $container
	 */
	protected function loadSshConnections(array $config, ContainerBuilder $container)
	{
		foreach ($config['connections'] as $name => $connectionConfig)
		{
			$serviceName = sprintf('ssh.session.%s', $name);
			$container->register($serviceName, 'Ssh\Session')
				->setFactory(['%ssh.session.factory.class%', 'getSession'])
				->setArguments([$connectionConfig])
			;
		}
	}
}
