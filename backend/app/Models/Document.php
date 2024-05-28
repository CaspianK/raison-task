<?php

namespace App\Models;

use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'origin_id',
        'path',
    ];

    protected $appends = [
        'file_name',
        'full_path'
    ];

    protected $hidden = [
        'path',
        'origin',
        'created_at',
        'updated_at'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Document $document) {
            $fileUploadService = new FileUploadService($document);
            $fileUploadService->delete($document->path, $document->origin->name);
        });
    }

    protected function fileName(): Attribute
    {
        return Attribute::make(
            get: fn () => basename($this->path)
        );
    }

    protected function fullPath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->origin->path . '/' . $this->path
        );
    }

    public function origin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FileOrigin::class);
    }
}
