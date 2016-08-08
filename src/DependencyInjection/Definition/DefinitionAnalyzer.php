<?php

/*
 * This file is part of Symplify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\DefaultAutowire\DependencyInjection\Definition;

use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\Definition;

final class DefinitionAnalyzer
{
    /**
     * @var DefinitionValidator
     */
    private $definitionValidator;

    public function __construct(DefinitionValidator $definitionValidator)
    {
        $this->definitionValidator = $definitionValidator;
    }

    public function shouldDefinitionBeAutowired(Definition $definition) : bool
    {
        if (!$this->definitionValidator->validate($definition)) {
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
            if (!isset($arguments[$i])) {
                if ($parameterReflection->isDefaultValueAvailable()) {
                    ++$i;
                    continue;
                }

                if (null !== $parameterReflection->getType()) {
                    return true;
                }
            }

            ++$i;
        }

        return false;
    }
}
