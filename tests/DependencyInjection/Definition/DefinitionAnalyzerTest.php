<?php

namespace Symplify\DefaultAutowire\Tests\DependencyInjection\Definition;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\DefaultAutowire\DependencyInjection\Definition\DefinitionAnalyzer;
use Symplify\DefaultAutowire\DependencyInjection\Definition\DefinitionValidator;
use Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource\EmptyConstructorClass;
use Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource\MissingArgumentsTypehintsClass;

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
        $definition = new Definition(EmptyConstructorClass::class);
        $this->assertFalse($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition));
    }

    public function testHaveMissingArgumentsTypehints()
    {
        $definition = new Definition(MissingArgumentsTypehintsClass::class);
        $definition->setArguments(['@someService']);

        $this->assertFalse($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition));
    }
}
