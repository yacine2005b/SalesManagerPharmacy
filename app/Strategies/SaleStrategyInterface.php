<?php
namespace App\Strategies;

interface SaleStrategyInterface
{
    public function processSale(array $cart, $request, $activeSession);
}