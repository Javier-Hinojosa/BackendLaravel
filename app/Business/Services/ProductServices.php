<?php

namespace App\Business\Services;
use App\Business\Entities\Taxes;

class ProductServices
{
    public function calculateIVA($price)
    {
        return $price * (1 + Taxes::IVA);
    }
}