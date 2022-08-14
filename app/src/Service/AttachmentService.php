<?php


namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Entity\Attachments;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AttachmentService extends AbstractController
{
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @return bool
     */

    public function createPhoto($file): Attachments
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uuidGenerator = Uuid::uuid4();
        $title = $uuidGenerator;
        $extension = $file->getClientOriginalExtension();
        $attachment = Attachments::create($title, $extension);
        $attachment->setUpdatedAt(new \DateTimeImmutable());
        $publicPath = $this->getParameter('kernel.project_dir');
        $file->move($publicPath . '/public/' . Attachments::PHOTO_PATH, $title . '.' . $extension);
        $entityManager->persist($attachment);
        return $attachment;
    }

    public function createDealScan($file): Attachments
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uuidGenerator = Uuid::uuid4();
        $title = $uuidGenerator;
        $extension = $file->getClientOriginalExtension();
        $attachment = Attachments::create($title, $extension);
        $attachment->setUpdatedAt(new \DateTimeImmutable());
        $publicPath = $this->getParameter('kernel.project_dir');
        $file->move($publicPath . '/public/' . Attachments::DEAL_PATH, $title . '.' . $extension);
        $entityManager->persist($attachment);
        return $attachment;
    }

    public function createIlustration($file): Attachments
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uuidGenerator = Uuid::uuid4();
        $title = $uuidGenerator;
        $extension = $file->getClientOriginalExtension();
        $attachment = Attachments::create($title, $extension);
        $attachment->setUpdatedAt(new \DateTimeImmutable());
        $publicPath = $this->getParameter('kernel.project_dir');
        $file->move($publicPath . '/public/' . Attachments::ILUSTRATION_PATH, $title . '.' . $extension);
        $entityManager->persist($attachment);
        return $attachment;
    }
}


