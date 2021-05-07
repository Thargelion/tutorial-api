<?php

namespace Roberto\api\user;


class UserRouter
{
    private static UserRouter $instance;

    private function __construct()
    {
    }

    public static function build(): UserRouter
    {
        if (self::$instance === null) {
            self::$instance = new UserRouter();
        }

        return self::$instance;
    }

    public function register()
    {
        $base = $_SERVER['REQUEST_URI'];
        echo $base;
        switch ($base) {
            case '/roberto-api/user':
                echo "pato";
        }
    }

}

