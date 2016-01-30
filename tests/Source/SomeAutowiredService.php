<?php

declare (strict_types = 1);

namespace Symplify\DefaultAutowire\Tests\Source;

class SomeAutowiredService
{
    /**
     * @var SomeService
     */
    private $someService;

    public function __construct(SomeService $someService)
    {
        $this->someService = $someService;
    }

    public function getSomeService() : SomeService
    {
        return $this->someService;
    }
}
