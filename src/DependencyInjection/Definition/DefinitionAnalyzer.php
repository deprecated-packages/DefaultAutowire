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

        $isFactory = $definition->getFactory() !== NULL;

        if ($isFactory) {
            return false;

        } else {
            return $this->shouldClassDefinitionBeAutowired($definition);
        }
    }

    private function shouldClassDefinitionBeAutowired(Definition $definition) : bool
    {
        $classReflection = new ReflectionClass($definition->getClass());
        if (!$classReflection->hasMethod('__construct') || !$this->hasConstructorArguments($classReflection)) {
            return false;
        }

        $constructorReflection = $classReflection->getConstructor();
        if ($this->areAllMethodArgumentsRequired($definition, $constructorReflection)) {
            return false;
        }

        if (!$this->haveMissingArgumentsTypehints($definition, $constructorReflection)) {
            return false;
        }

        return true;
    }

    private function hasConstructorArguments(ReflectionClass $classReflection) : bool
    {
        $constructorMethodReflection = $classReflection->getConstructor();
        if ($constructorMethodReflection->getNumberOfParameters()) {
            return true;
        }

        return false;
    }

    private function areAllMethodArgumentsRequired(
        Definition $definition,
        ReflectionMethod $constructorReflection
    ) : bool {
        $constructorArgumentsCount = count($definition->getArguments());
        $constructorRequiredArgumentsCount = $constructorReflection->getNumberOfRequiredParameters();

        if ($constructorArgumentsCount === $constructorRequiredArgumentsCount) {
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
