<?php

/*
 * This file is part of Symplify
 * Copyright (c) 2016 Tomas Votruba (http://tomasvotruba.cz).
 */

namespace Symplify\DefaultAutowire;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symplify\DefaultAutowire\DependencyInjection\Compiler\DefaultAutowireTypesCompilerPass;
use Symplify\DefaultAutowire\DependencyInjection\Compiler\TurnOnAutowireCompilerPass;
use Symplify\DefaultAutowire\DependencyInjection\Extension\SymplifyDefaultAutowireContainerExtension;

final class SymplifyDefaultAutowireBundle extends Bundle
{
    /**
     * @var string
     */
    const ALIAS = 'symplify_default_autowire';

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addCompilerPass(new DefaultAutowireTypesCompilerPass());
        $containerBuilder->addCompilerPass(new TurnOnAutowireCompilerPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension() : SymplifyDefaultAutowireContainerExtension
    {
        return new SymplifyDefaultAutowireContainerExtension();
    }
}
