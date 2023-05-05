<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class FileService extends AbstractController
{
    private $slugger;
    private $filesystem;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->filesystem = new Filesystem();
    }

    /**
     * @param $file
     * @param $directory
     * @return string
     */
    public function UploadFile($file, $directory)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getParameter($directory),
                $newFilename
            );
        } catch (FileException $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return $newFilename;
    }

    /**
     * @param $fileName
     * @param $directory
     */
    public function DeleteFile($fileName, $directory){
        $fullPath = $this->getParameter($directory).'/'.$fileName;

        if($this->filesystem->exists($fullPath)){
            $this->filesystem->remove($fullPath);
        }
    }

}