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

        if (!$this->hasConstructorArguments($classReflection)) {
            return false;
        }

        if ($this->areAllConstructorArgumentsRequired($definition, $classReflection)) {
            return false;
        }

        $constructorReflection = $classReflection->getConstructor();
        if (!$this->haveMissingArgumentsTypehints($definition, $constructorReflection)) {
            return false;
        }

        return true;
    }

    private function isDefinitionValid(Definition $definition) : bool
    {
        if (null === $definition->getClass()) {
            return false;
        }

        if (!$definition->isPublic()) {
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

    private function areAllConstructorArgumentsRequired(Definition $definition, ReflectionClass $classReflection) : bool
    {
        $constructorMethodReflection = $classReflection->getConstructor();

        $constructorArgumentsCount = count($definition->getArguments());
        $constructorRequiredArgumentsCount = $constructorMethodReflection->getNumberOfRequiredParameters();

        if ($constructorArgumentsCount === $constructorRequiredArgumentsCount) {
            return true;
        }

        return false;
    }

    private function hasConstructorArguments(ReflectionClass $classReflection) : bool
    {
        $constructorMethodReflection = $classReflection->getConstructor();
        if ($constructorMethodReflection->getNumberOfParameters()) {
            return true;
        }

        return false;
    }

    private function haveMissingArgumentsTypehints(
        Definition $definition,
        ReflectionMethod $constructorReflection
    ) : bool {
        $arguments = $definition->getArguments();
        if (!count($arguments)) {
            return true;
        }

        $i = 0;
        foreach ($constructorReflection->getParameters() as $parameterReflection) {
            if ($arguments[$i] === '' && $parameterReflection->getType() !== null) {
                return true;
            }
            ++$i;
        }

        return false;
    }
}
