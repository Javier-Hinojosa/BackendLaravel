<?php

use App\Business\Entities\Taxes;
use App\Business\Services\ProductServices;

test('Calcula el impuesto del IVA', function () {
    $product = new ProductServices();
    $price = 100;

    $result = $product->calculateIVA($price);

    expect($result)->toBe($price * (1 + Taxes::IVA));
});
