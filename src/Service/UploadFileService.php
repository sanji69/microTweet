<?php


namespace App\Service;


use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;

class UploadFileService
{
    private FilesystemInterface $tweetsStorage;

    public function __construct(FilesystemInterface $tweetsStorage)
    {
        $this->tweetsStorage = $tweetsStorage;
    }

    public function upload($path)
    {
        dd($path);
        $this->tweetsStorage->read($path);
    }
}