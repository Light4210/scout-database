<?php

// src/Twig/AppExtension.php
namespace App\Twig\Functions;

use App\Entity\Attachments;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Attachment extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('attachmentPhoto', [$this, 'attachmentPhoto']),
            new TwigFunction('attachmentDeal', [$this, 'attachmentDeal']),
        ];
    }

    public function attachmentPhoto(null|Attachments|UploadedFile $attachment): ?string
    {
        if(!$attachment){
            return null;
        }
        if($attachment instanceof UploadedFile){
            return null;
        }
        $photoPath = Attachments::PHOTO_PATH;
        $title = $attachment->getTitle();
        $extension = $attachment->getExtension();
        $imgPath = "/$photoPath/$title.$extension";
        return $imgPath;
    }

    public function attachmentSvg(?Attachments $attachment): ?string
    {
        if(!$attachment){
            return null;
        }
        $dealPath = Attachments::DEAL_PATH;
        $title = $attachment->getTitle();
        $extension = $attachment->getExtension();
        $imgPath = "/$dealPath/$title.$extension";
        return $imgPath;
    }

    public function attachmentDeal(?Attachments $attachment): ?string
    {
        if(!$attachment){
            return null;
        }
        $dealPath = Attachments::DEAL_PATH;
        $title = $attachment->getTitle();
        $extension = $attachment->getExtension();
        $imgPath = "/$dealPath/$title.$extension";
        return $imgPath;
    }
}