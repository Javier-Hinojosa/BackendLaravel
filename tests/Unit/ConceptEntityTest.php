<?php

use App\Business\Entities\ConceptEntity;

test('Creación correcta de venta', function () {
    $concept = new ConceptEntity(
        quantity: 2,
        price: 100.0,
        product_id:1
    );

    expect($concept->quantity)->toBe(2)
        ->and($concept->price)->toBe(100.0)
        ->and($concept->product_id)->toBe(1)
        ->and($concept->total)->toBe(200.0);
});

test('calulo correcto del total', function () {
    $concept = new ConceptEntity(
        quantity: 3,
        price: 50.0,
        product_id:2
    );

    expect($concept->total)->toBe(150.0);
});