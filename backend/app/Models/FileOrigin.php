<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileOrigin extends Model
{
    use HasFactory;

    const ORIGIN_S3 = 's3';
    const ORIGIN_LOCAL = 'local';
}
