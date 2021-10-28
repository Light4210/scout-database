<?php


namespace App\Service;

use App\Entity\Attachments;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentService extends AbstractController
{
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     * @return bool
     */
    public function createAttachment($file, $type): Attachments
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uuidGenerator = Uuid::uuid4();

        $title = $uuidGenerator;
        $extension = $file->getClientOriginalExtension();
        $attachment = Attachments::create($title, $extension);
        $attachment->setUpdatedAt(new \DateTimeImmutable());
        $publicPath = $this->getParameter('kernel.project_dir');
        if ($type == key(Attachments::DEAL)) {
            $file->move($publicPath . '/public/' . Attachments::DEAL['dealScan'], $title . '.' . $extension);
        } elseif ($type == key(Attachments::AVATAR)) {
            $file->move($publicPath . '/public/' . Attachments::AVATAR['photo'], $title . '.' . $extension);
        }
        $entityManager->persist($attachment);
        return $attachment;
    }
}


