<?php

namespace Roberto\api;

use Roberto\api\user\UserRouter;

class Main
{
    public static function run()
    {
        $userRouter = UserRouter::build();
        $userRouter->register();
    }
}