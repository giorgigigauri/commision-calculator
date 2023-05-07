<?php

namespace App\CommissionProcessors;

abstract class CommissionProcessor
{
    abstract public function calculate(): float;
}
