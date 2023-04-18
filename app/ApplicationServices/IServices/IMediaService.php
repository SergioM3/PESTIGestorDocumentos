<?php

namespace App\ApplicationServices\IServices;

use Illuminate\Http\Request;

interface IMediaService
{
    public function saveTemporaryFile(Request $request);
    public function encryptFile(string $filePath);
    public function decryptFile(string $filePath);
    public function getTemporaryFileByFolder(string $folder);
}
