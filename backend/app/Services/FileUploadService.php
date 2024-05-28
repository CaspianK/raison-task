<?php

namespace App\Services;

use App\Models\Document;
use App\Models\FileOrigin;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{

    private Document $document;
    private string $extension;

    const SUPPORTED_EXTENSIONS = ['pdf', 'jpg'];
    const ORIGINS = [
        'jpg' => FileOrigin::ORIGIN_LOCAL,
        'pdf' => FileOrigin::ORIGIN_S3
    ];

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @throws Exception
     */
    public function upload($file): Document
    {
        $this->setExtension($file);
        $this->getOrigin();
        $this->store($file);

        return $this->document;
    }

    public function delete(): void
    {
        Storage::disk($this->document->origin->name)->delete($this->document->path);
    }

    /**
     * @throws Exception
     */
    private function setExtension($file): void
    {
        $extension = $file->getClientOriginalExtension();

        if (in_array($extension, self::SUPPORTED_EXTENSIONS, true)) {
            $this->extension = $file->getClientOriginalExtension();
        } else {
            throw new Exception("Unsupported file type");
        }

    }

    /**
     * @throws Exception
     */
    public function getOrigin(): void
    {
        $originName = self::ORIGINS[$this->extension] ?? null;

        if (!$originName) {
            throw new Exception("Error determining file origin.");
        }

        $origin = FileOrigin::where('name', $originName)->first();

        if (!$origin) {
            throw new Exception("Error uploading file. Origin not found.");
        }

        $this->document->origin_id = $origin->id;
    }

    /**
     * @throws Exception
     */
    private function store($file): void
    {
        $path = '';

        if ($this->extension === 'pdf') {
            $path = $this->storePDF($file);
        } elseif ($this->extension === 'jpg' || $this->extension === 'jpeg') {
            $path = $this->storeJPG($file);
        }

        if (!$path) {
            throw new Exception("Error uploading file");
        }

        $this->document->path = $path;
    }

    private function storePDF($file): false|string
    {
        return Storage::disk(FileOrigin::ORIGIN_S3)->putFile('/uploads/purchases', $file);
    }

    private function storeJPG($file): false|string
    {
        return Storage::disk(FileOrigin::ORIGIN_LOCAL)->putFile('/uploads/purchases', $file);
    }
}
