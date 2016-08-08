<?php

namespace Symplify\DefaultAutowire\Tests\DependencyInjection\Definition\DefinitionAnalyzerSource;

use Symplify\DefaultAutowire\Tests\Source\SomeService;

final class MissingArgumentsTypehintsClass
{
    public function __construct(SomeService $someService = null, $value = 1, $valueWithoutType)
    {
    }
}
