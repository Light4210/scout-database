<?php

// src/Twig/AppExtension.php
namespace App\Twig\Filters;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class IsEmpty extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('isEmpty', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice(int|string|null $var): string
    {
        if(!empty($var)){
            return $var;
        } else{
            return '-';
        }
    }
}