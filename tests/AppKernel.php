<?php

namespace Symplify\DefaultAutowire\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symplify\DefaultAutowire\SymplifyDefaultAutowireBundle;

final class AppKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new SymplifyDefaultAutowireBundle(),
            new DoctrineBundle()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/Resources/config/config.yml');
    }
}
