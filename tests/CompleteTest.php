<?php

namespace Symplify\DefaultAutowire\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symplify\DefaultAutowire\Tests\Source\SomeAutowiredService;
use Symplify\DefaultAutowire\Tests\Source\SomeService;

final class CompleteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SomeAutowiredService
     */
    private $someAutowiredService;

    protected function setUp()
    {
        $kernel = new AppKernel('test_env', true);
        $kernel->boot();

        $this->someAutowiredService = $kernel->getContainer()
            ->get('some_autowired_service');
    }

    public function testIsAutowired()
    {
        $this->assertInstanceOf(
            SomeService::class,
            $this->someAutowiredService->getSomeService()
        );
    }
}
