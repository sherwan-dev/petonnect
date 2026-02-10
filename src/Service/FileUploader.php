<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;
class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
        private LoggerInterface $logger,
    ) {
    }

    public function upload(UploadedFile $file, string $subDirectory = ''): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $uploadPath = $this->getTargetDirectory();
        if ($subDirectory !== '') {
            $uploadPath .= $subDirectory;
        }

        try {
            $file->move($uploadPath, $fileName);
        } catch (FileException $e) {
            $this->logger->error('File upload failed', [
                'original_name' => $file->getClientOriginalName(),
                'directory' => 'uploads/'.$subDirectory,
                'exception' => $e,
            ]);

            throw $e;
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}