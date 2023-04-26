<?php

namespace App\ApplicationServices\IServices;

use App\Domain\Aggregates\Document\TemporaryFile;
use Illuminate\Http\Request;

interface IMediaService
{
    /**
     * Encrypts file from request form with key and chipher set at config/app.php and then
     * Saves it to storage/app/env('MEDIA_TEMP_FOLDER')/UniqueFolderName
     *
     * @param  Request $request
     * @return mixed # Can return TemporaryFile if success or string if error
     */
    public function saveTemporaryFile(Request $request);

    /**
     * Encrypts a file according to config/app.php configuration algorithms
     *
     * @param  string $filePath # Path to file to encrypt :
     * @return string # returns an encrypted string of the file
     */
    public function encryptFile(string $filePath): string;

    /**
     * Decrypts a file according to config/app.php configuration algorithms
     *
     * @param  string $filePath
     * @return string # returns a decrypted binary of the file
     */
    public function decryptFile(string $filePath): string;

    /**
     * Returns first temporary file instance inside a folder
     *
     * @param  string $folder #Folder of the temporary file to get. Doesn't need a path, just the folder
     * @return TemporaryFile
     */
    public function getTemporaryFileByFolder(string $folder): TemporaryFile;
}
