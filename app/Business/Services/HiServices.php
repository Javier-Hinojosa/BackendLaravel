<?php

namespace App\Business\Services;

use App\Business\Interfaces\MessageServiceInterface;

class HiServices implements MessageServiceInterface
{
    public function sayHi($name)
    {
        return "Hi, $name!";
    }

    public function hi(): void
    {
        echo "Hi!";
    }


}