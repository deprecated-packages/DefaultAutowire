<?php

namespace Symplify\DefaultAutowire\Tests\Config\Definition;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\DefaultAutowire\Config\Definition\ConfigurationResolver;
use Symplify\DefaultAutowire\DependencyInjection\Extension\SymplifyDefaultAutowireContainerExtension;
use Symplify\DefaultAutowire\SymplifyDefaultAutowireBundle;

final class ConfigurationResolverTest extends TestCase
{
    /**
     * @var ConfigurationResolver
     */
    private $configurationResolver;

    protected function setUp()
    {
        $this->configurationResolver = new ConfigurationResolver();
    }

//    public function testResolveDefaults()
//    {
//        $resolvedConfiguration = $this->configurationResolver->resolveFromContainerBuilder(new ContainerBuilder());
//
//        $this->assertSame([
//            'autowire_types' => [
//                'Doctrine\ORM\EntityManager' => 'doctrine.orm.default_entity_manager',
//                'Doctrine\ORM\EntityManagerInterface' => 'doctrine.orm.default_entity_manager',
//                'Doctrine\Portability\Connection' => 'database_connection',
//                'Symfony\Component\EventDispatcher\EventDispatcher' => 'event_dispatcher',
//                'Symfony\Component\EventDispatcher\EventDispatcherInterface' => 'event_dispatcher',
//                'Symfony\Component\Translation\TranslatorInterface' => 'translator',
//            ]
//        ], $resolvedConfiguration);
//    }

    public function testOverrideDefaults()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->prependExtensionConfig(SymplifyDefaultAutowireBundle::ALIAS, [
            'autowire_types' => [
                'Doctrine\ORM\EntityManager' => 'other_entity_manager'
            ]
        ]);

        $resolvedConfiguration = $this->configurationResolver->resolveFromContainerBuilder(
            $containerBuilder
        );

        $this->assertSame('other_entity_manager', $resolvedConfiguration['autowire_types']['Doctrine\ORM\EntityManager']);
        $this->assertCount(6, $resolvedConfiguration['autowire_types']);
    }
}
