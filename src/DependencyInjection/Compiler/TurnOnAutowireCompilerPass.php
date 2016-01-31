<?php

declare (strict_types = 1);

/*
 * This file is part of Symplify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\DefaultAutowire\DependencyInjection\Compiler;

use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class TurnOnAutowireCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $containerBuilder)
    {
        foreach ($containerBuilder->getDefinitions() as $definition) {
            if ($this->shouldDefinitionBeAutowired($definition)) {
                $definition->setAutowired(true);
            }
        }
    }

    private function shouldDefinitionBeAutowired(Definition $definition) : bool
    {
        if (!$this->isDefinitionValid($definition)) {
            return false;
        }

        $classReflection = new ReflectionClass($definition->getClass());
        if (!$classReflection->hasMethod('__construct')) {
            return false;
        }

        $constructorMethodReflection = $classReflection->getConstructor();

        $argumentsCount = count($definition->getArguments());
        $requiredArgumentsCount = $constructorMethodReflection->getNumberOfRequiredParameters();

        if ($argumentsCount === $requiredArgumentsCount) {
            return false;
        }

        return true;
    }

    private function isDefinitionValid(Definition $definition) : bool
    {
        if (null === $definition->getClass()) {
            return false;
        }

        if ($definition->isAbstract()) {
            return false;
        }

        if (!class_exists($definition->getClass())) {
            return false;
        }

        return true;
    }
}
