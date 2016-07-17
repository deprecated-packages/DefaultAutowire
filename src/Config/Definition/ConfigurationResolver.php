<?php

/*
 * This file is part of Symplify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\DefaultAutowire\Config\Definition;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symplify\DefaultAutowire\SymplifyDefaultAutowireBundle;

final class ConfigurationResolver
{
    /**
     * @var string[]
     */
    private $resolvedConfiguration;

    /**
     * {@inheritdoc}
     */
    public function resolveFromContainerBuilder(ContainerBuilder $containerBuilder)
    {
        if (!$this->resolvedConfiguration) {
            $processor = new Processor();
            $configs = $containerBuilder->getExtensionConfig(SymplifyDefaultAutowireBundle::ALIAS);
            $configs = $processor->processConfiguration(new Configuration(), $configs);

            $this->resolvedConfiguration = $containerBuilder->getParameterBag()->resolveValue($configs);
        }

        return $this->resolvedConfiguration;
    }
}
