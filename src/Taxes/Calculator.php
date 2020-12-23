<?php

namespace App\Taxes;

class Calculator
{
    protected $tva;

    public function __construct(float $tva)
    {
        $this->tva = $tva;
    }


    public function calcul(float $prix): float
    {
        return $prix * ($this->tva / 100);
    }
}
