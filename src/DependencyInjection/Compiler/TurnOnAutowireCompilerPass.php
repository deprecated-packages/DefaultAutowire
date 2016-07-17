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
        foreach ($containerBuilder->getDefinitions() as $name => $definition) {
            if ($this->shouldDefinitionBeAutowired($name, $definition)) {
                $definition->setAutowired(true);
            }
        }
    }

    /**
     * @param string $name
     * @param Definition $definition
     * @return bool
     */
    private function shouldDefinitionBeAutowired($name, Definition $definition)
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

    /**
     * @return bool
     */
    private function isDefinitionValid(Definition $definition)
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

    /**
     * @return bool
     */
    private function hasConstructorArguments(ReflectionClass $classReflection)
    {
        $constructorMethodReflection = $classReflection->getConstructor();
        if ($constructorMethodReflection->getNumberOfParameters()) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function haveMissingArgumentsTypehints(Definition $definition, ReflectionMethod $constructorReflection)
    {
        $arguments = $definition->getArguments();
        if (!count($arguments)) {
            return true;
        }

        $i = 0;
        foreach ($constructorReflection->getParameters() as $parameterReflection) {
            if ($arguments[$i] === "" && $parameterReflection->getType() !== NULL) {
                return true;
            }
            $i++;
        }

        return false;
    }
}
