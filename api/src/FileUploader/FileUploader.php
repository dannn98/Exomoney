<?php

namespace App\FileUploader;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;
    private string $targetUrl;
    private SluggerInterface $slugger;

    /**
     * FileUploader constructor
     *
     * @param string $targetDirectory
     * @param string $targetUrl
     * @param SluggerInterface $slugger
     */
    public function __construct(string $targetDirectory, string $targetUrl, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetUrl = $targetUrl;
        $this->slugger = $slugger;
    }

    /**
     * Upload method
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Get target directory
     *
     * @return string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    /**
     * Get target Url
     *
     * @return string
     */
    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }
}