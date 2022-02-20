<?php

// src/Twig/AppExtension.php
namespace App\Twig\Functions;

use App\Helper\SvgHelper;
use App\Entity\Attachments;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DisplaySvg extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('svg', [$this, 'svg']),
            new TwigFunction('svgPath', [$this, 'svgPath']),
        ];
    }

    public function svgPath(string $svgName): ?string
    {
        if(!$svgName && in_array($svgName, SvgHelper::SVG_NAMES)){
            return null;
        }
        $svgPath = SvgHelper::SVG_PATH;
        $svgPath = "/$svgPath/$svgName.svg";
        return $svgPath;
    }

    public function svg(string $svgName): ?string
    {
        if(!$svgName && in_array($svgName, SvgHelper::SVG_NAMES)){
            return null;
        }
        $svgPath = SvgHelper::SVG_PATH;
        $svgPath = "./$svgPath/$svgName.svg";
        return file_get_contents($svgPath);
    }
}