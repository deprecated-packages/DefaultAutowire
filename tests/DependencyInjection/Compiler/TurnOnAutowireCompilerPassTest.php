<?php

namespace Symplify\DefaultAutowire\Tests\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\DefaultAutowire\DependencyInjection\Compiler\TurnOnAutowireCompilerPass;
use Symplify\DefaultAutowire\Tests\Resources\Repository\SomeRepository;
use Symplify\DefaultAutowire\Tests\Source\SomeAutowiredService;

final class TurnOnAutowireCompilerPassTest extends TestCase
{
    public function testProcess()
    {
        $turnOnAutowireCompilerPass = new TurnOnAutowireCompilerPass();

        $containerBuilder = new ContainerBuilder();

        $autowiredDefinition = $containerBuilder->setDefinition(
            'some_autowired_service',
            new Definition(SomeAutowiredService::class)
        );
        $this->assertFalse($autowiredDefinition->isAutowired());

        $turnOnAutowireCompilerPass->process($containerBuilder);
        $this->assertTrue($autowiredDefinition->isAutowired());
    }
}
