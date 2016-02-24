<?php

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

    /**
     * @return SomeService
     */
    public function getSomeService()
    {
        return $this->someService;
    }
}
