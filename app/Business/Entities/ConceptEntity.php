<?php

namespace App\Business\Entities;

class ConceptEntity
{
    public $quantity;
    public $price;
    public $product_id;
    public $total;

    public function __construct($quantity, $price, $product_id)
    {
        $this->quantity = $quantity;
        $this->price = $price;
        $this->product_id = $product_id;
        $this->total = $this->calcultateTotal();
    }

    public function calcultateTotal()
    {
        return  $this->quantity * $this->price;
    }
}