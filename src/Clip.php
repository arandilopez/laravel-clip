<?php

namespace Clip;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class Clip
{
    protected $disk;
    protected $basedir;
    protected $model;

    public function __construct()
    {
        $this->disk = Config::get('clip.disk', 'local');
        $this->basedir = Config::get('clip.basedir', 'attachments');
        $this->model = Config::get('clip.model', 'Clip\Model\Attachment');
    }

    /**
     * Create a new Attachment model
     * @param  mixed  $file
     * @param  mixed $filename
     * @param  mixed $dir
     * @return mixed
     */
    public function attach($file, $filename = false, $dir = false)
    {
        if ($file instanceof string && File::isFile($file)) {
            return $this->attachFromFileName($file);
        } elseif ($file instanceof UploadedFile) {
            return $this->attachFromUploadedFile($file, $filename, $dir);
        } else {
            throw new Exception("You must provide a file", 1);
        }
    }

    /**
     * create an Attachment from an UploadedFile
     * @param  UploadedFile $file
     * @param  mixed      $filename
     * @param  mixed      $dir
     * @return mixed
     */
    public function attachFromUploadedFile(UploadedFile $file, $filename = false, $dir = false)
    {
        $path = '';
        $file_path = $file->storeAs($path, $filename, ['disk' => $this->disk]);

        return new $this->model([
            'file_name' => $file->getBasename(),
            'file_size' => $file->getSize(),
            'file_content_type' => $file->getMimeType(),
            'file_path' => $file_path
        ]);
    }

    /**
     * Create a new Attachment from a path
     * @param  string  $path
     * @param  mixed $filename
     * @param  mixed $dir
     * @return mixed
     */
    public function attachFromFileName(string $path, $filename = false, $dir = false)
    {
        if (File::isReadable($path)) {
            $fileContent = File::get($path);
            $filename = ($filename) ? $filename : File::basename($path);
            $filePath = $this->basedir .
                (($dir) ? '/' . $dir : '') .
                $filename;
            Storage::disk($this->disk)
            ->put($filePath, $fileContent);

            return new $this->model([
                'file_name' => $filename,
                'file_size' => File::size($path),
                'file_content_type' => File::mimeType($path),
                'file_path' => $filePath
            ]);
        } else {
            throw new Exception("You must provide a valid file", 1);
        }
    }
}
