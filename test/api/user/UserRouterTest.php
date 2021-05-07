<?php

use PHPUnit\Framework\TestCase;
use Roberto\api\user\UserRouter;


class UserRouterTest extends TestCase
{
    public function testBuildBuildsUserRouter()
    {
        $routerLoco = UserRouter::build();
        $this->assertInstanceOf(UserRouter::class, $routerLoco);
    }
}