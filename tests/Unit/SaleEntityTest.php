<?php

use App\Business\Entities\ConceptEntity;
use App\Business\Entities\SaleEntity;

test('Creacion de SaleEntity correctamente', function () {
    $conept1 = new ConceptEntity(
        quantity: 2,
        price: 100.0,
        product_id:1
    );
    $conept2 = new ConceptEntity(
        quantity: 3,
        price: 50.0,
        product_id:2
    );

    $saleEntity = new SaleEntity(
        id: 1,
        email: 'test@example.com',
        sale_date: '2024-06-01 16:00:00',
        concepts: [$conept1, $conept2]
    );
    expect($saleEntity->id)->toBe(1)
        ->and($saleEntity->email)->toBe('test@example.com')
        ->and($saleEntity->sale_date)->toBe('2024-06-01 16:00:00')
        ->and($saleEntity->concepts)->toBeArray()
        ->and($saleEntity->concepts)->toHaveCount(2);

});

test('Cálculo correcto del total en SaleEntity', function () {
    
// El total del primer concepto es 200.0 (2 x 100.0)
    $conept1 = new ConceptEntity(
        quantity: 2,
        price: 100.0,
        product_id: 1
    );
    // El total del segundo concepto es 150.0 (3 x 50.0)
    $conept2 = new ConceptEntity(
        quantity: 3,
        price: 50.0,
        product_id: 2
    );
    // El total de la venta debe ser 350.0 (200.0 + 150.0)

    $saleEntity = new SaleEntity(
        id: 1,
        email: 'test@example.com',
        sale_date: '2024-06-01 16:00:00',
        concepts: [$conept1, $conept2]
    );

    expect($saleEntity->total)->toBe(350.0);

});

test('Cálculo del total con conceptos vacíos en SaleEntity', function () {
    $saleEntity = new SaleEntity(
        id: 1,
        email: 'test@example.com',
        sale_date: '2024-06-01 16:00:00',
        concepts: []
    );

    expect($saleEntity->total)->toBe(0.0);

});
