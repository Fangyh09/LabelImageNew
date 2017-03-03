<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = true;
    protected $table = 'images';
    protected $fillable = ['filename'];

    public function getFileNamePre($str) {
        $path_parts = pathinfo($str);
        return $path_parts['filename'];
    }
}


