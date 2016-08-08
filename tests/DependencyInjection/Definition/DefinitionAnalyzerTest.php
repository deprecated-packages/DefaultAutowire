<?php

namespace Symplify\DefaultAutowire\Tests\DependencyInjection\Definition;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\DefaultAutowire\DependencyInjection\Definition\DefinitionAnalyzer;
use Symplify\DefaultAutowire\DependencyInjection\Definition\DefinitionValidator;
use Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource\EmptyConstructor;
use Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource\MissingArgumentsTypehints;
use Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource\NotMissingArgumentsTypehints;

final class DefinitionAnalyzerTest extends TestCase
{
    /**
     * @var DefinitionAnalyzer
     */
    private $definitionAnalyzer;

    protected function setUp()
    {
        $this->definitionAnalyzer = new DefinitionAnalyzer(new DefinitionValidator());
    }

    public function testHasConstructorArguments()
    {
        $definition = new Definition(EmptyConstructor::class);
        $this->assertFalse($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition));
    }

    public function testHaveMissingArgumentsTypehints()
    {
        $definition = new Definition(MissingArgumentsTypehints::class);
        $definition->setArguments(['@someService']);

        $this->assertFalse($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition));
    }

    public function testHaveNotMissingArgumentsTypehints()
    {
        $definition = new Definition(NotMissingArgumentsTypehints::class);
        $definition->setArguments(['@someService']);

        $this->assertTrue($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition));
    }
}
