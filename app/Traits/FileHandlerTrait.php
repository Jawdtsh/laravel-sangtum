<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait FileHandlerTrait
{

    //first way to generate name secure , unique
    public function GenerateUniqueFileNameWithUUID($file): ?string
    {
        if (!$file) {
            return null;
        }
        $uuid = Str::uuid()->toString();
        $extension = $file->extension();

        $newNameWithoutExtension = pathinfo($uuid, PATHINFO_FILENAME);
        return  $newNameWithoutExtension. '.' . $extension;
    }


    //second way to generate name secure , unique
    public function generateUniqueFileNameWithHash($file): ?string
    {
        if ($file === null) {
            return null;
        }

        $newName = $file->hashName();
        $extension = $file->extension();

        $newNameWithoutExtension = pathinfo($newName, PATHINFO_FILENAME);

        return $newNameWithoutExtension . '.' . $extension;
    }


}
