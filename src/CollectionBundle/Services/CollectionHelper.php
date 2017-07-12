<?php
namespace CollectionBundle\Services;

use CollectionBundle\Model\Attachment;
use CollectionBundle\Model\Collection;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class CollectionHelper
{
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->rootDir = $this->container->get('kernel')->getRootDir() . '/';
    }

    public function info()
    {
        return "This class holds various functions for collection related functionality";
    }

    public function checkCollectionRoot(Collection $collection)
    {
        $fs = new Filesystem();
        $filepath = $this->rootDir . $this->container->getParameter('collection_path') . '/' . $collection->getUid();
        if (!$fs->exists($filepath)) {
            try {
                $fs->mkdir($filepath);
            } catch (IOExceptionInterface $e) {
                return false;
            }
        }
        return $filepath . '/';
    }

    public function saveCollectionAttachment(Collection $collection, $fileInfo)
    {
        $filePath = $this->checkCollectionRoot($collection);
        if (!$filePath)
            return false;

        $fileUUID = $this->createFileUUID();
        if (empty($fileUUID))
            return false;

        $originalName = $fileInfo->getClientOriginalName();
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = $fileUUID . '.' . $ext;

        $fileInfo->move($filePath, $filename);

        $attachment = new Attachment();
        $attachment->setUid($this->createUUID());
        $attachment->setOriginalName($originalName);
        $attachment->setFilename($filename);
        $attachment->setName($filename);
        $attachment->setType($this->getAttachementType($originalName));
        $attachment->setLinkUrl($filePath . $filename);
        $attachment->save();

        $collection->addAttachment($attachment);
        $collection->save();
    }

    public function deleteCollectionAttachment(Collection $collection, Attachment $attachment)
    {
        $fs = new Filesystem();
        try {
            $fs->remove($attachment->getLinkUrl());
        } catch (IOExceptionInterface $e) {
            return false;
        }
        $collection->removeAttachment($attachment);
        $collection->save();
        $attachment->delete();
        return true;
    }

    public function rotateImageAttachment(Attachment $attachment)
    {
        $filename = $attachment->getLinkUrl();
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $source = null;

        switch ($ext)
        {   case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($filename);
                if (empty($source))
                    return false;
                $rotated = imagerotate($source, 90, 0);
                imagejpeg($rotated, $filename);
                imagedestroy($source);
                imagedestroy($rotated);
                break;
            case 'png':
                $source = imagecreatefrompng($filename);
                if (empty($source))
                    return false;
                $rotated = imagerotate($source, 90, 0);
                imagepng($rotated, $filename);
                imagedestroy($source);
                imagedestroy($rotated);
                break;
        }
        return true;
    }

    public function createUUID()
    {
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            return $uuid;
        }
        return null;
    }

    public function createFileUUID()
    {
        return str_replace('-', '', $this->createUUID());
    }

    public function getAttachementType($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png':
            case 'jpeg':
            case 'jpg':
                return Attachment::TYPE_IMAGE;
            case 'pdf';
                return Attachment::TYPE_DOCUMENT;
        }
        return Attachment::TYPE_UNKNOWN;
    }
}