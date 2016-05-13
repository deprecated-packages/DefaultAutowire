<?php

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

    /**
     * @return bool
     */
    private function shouldDefinitionBeAutowired(Definition $definition)
    {
        if (!$this->isDefinitionValid($definition)) {
            return false;
        }

        $classReflection = new ReflectionClass($definition->getClass());
        if (!$classReflection->hasMethod('__construct')) {
            return false;
        }

        if ($this->areAllConstructorArgumentsRequired($definition, $classReflection)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    private function isDefinitionValid(Definition $definition)
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

    /**
     * @return bool
     */
    private function areAllConstructorArgumentsRequired(Definition $definition, ReflectionClass $classReflection)
    {
        $constructorMethodReflection = $classReflection->getConstructor();

        $constructorArgumentsCount = count($definition->getArguments());
        $constructorRequiredArgumentsCount = $constructorMethodReflection->getNumberOfRequiredParameters();

        if ($constructorArgumentsCount === $constructorRequiredArgumentsCount) {
            return true;
        }

        return false;
    }
}
