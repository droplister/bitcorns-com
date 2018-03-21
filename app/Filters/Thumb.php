<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Thumb implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}