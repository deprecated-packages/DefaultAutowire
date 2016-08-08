<?php

namespace Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource;

final class EmptyConstructorClass
{
    public function __construct()
    {
        $value = 1;
    }
}
