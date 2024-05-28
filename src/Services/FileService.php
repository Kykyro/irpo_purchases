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
use ZipArchive;

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
     * @param $file
     * @param $directory
     * @return string
     */
    public function UploadFilesZip($files, $directory)
    {
        $fileName = "file". uniqid() .".zip";

        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $zip = new ZipArchive();

        $zip->open($temp_file, \ZipArchive::CREATE);

        foreach ($files as $file)
        {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . ' ' . uniqid() . '.' . $file->guessExtension();

            $zip->addFile($file, $newFilename);
        }

        $status = $zip->close();

        try {
            if (!file_exists($this->getParameter($directory))) {
                mkdir($this->getParameter($directory), 777, true);
            }
            $newFile = $this->getParameter($directory).'/'.$fileName;
            rename($temp_file, $newFile);
        } catch (FileException $e) {
            throw new HttpException(500, $e->getMessage());
        }

        return $fileName;
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