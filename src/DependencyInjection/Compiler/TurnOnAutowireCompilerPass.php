<?php

/*
 * This file is part of Symplify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\DefaultAutowire\DependencyInjection\Compiler;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symplify\DefaultAutowire\DependencyInjection\Definition\DefinitionAnalyzer;

final class TurnOnAutowireCompilerPass implements CompilerPassInterface
{
    /**
     * @var DefinitionAnalyzer
     */
    private $definitionAnalyzer;

    public function __construct(DefinitionAnalyzer $definitionAnalyzer)
    {
        $this->definitionAnalyzer = $definitionAnalyzer;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $containerBuilder)
    {
        foreach ($containerBuilder->getDefinitions() as $definition) {
            if ($this->definitionAnalyzer->shouldDefinitionBeAutowired($definition)) {
                $definition->setAutowired(true);
            }
        }
    }
}
