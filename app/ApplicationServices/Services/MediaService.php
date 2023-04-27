<?php

namespace App\ApplicationServices\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use App\Domain\Aggregates\Document\TemporaryFile;
use App\ApplicationServices\IServices\IMediaService;

class MediaService implements IMediaService
{
    /**
     *This service funtion is saving the file on the request to a temporary location : storage/app/uploads
     * and saving it's meta to a database table "TemporaryFile"
     * Then it encrypts the file from request form with key and chipher set at config/app.php and then
     * overwrites it on it to storage/app/env('MEDIA_TEMP_FOLDER')/UniqueFolderName
     *
     * @param  Request $request
     * @return mixed # Can return TemporaryFile if success or string if error
     */
    public function saveTemporaryFile(Request $request)
    {
        try {
            $request->validate([
                'upload_file' => env('MEDIA_FILE_VALIDATION')
            ]);

            if ($request->hasFile('upload_file')) {
                $file = $request->file('upload_file');
                $filename = $file->getClientOriginalName();
                $folder = uniqid() . '-' . now()->timestamp;

                // Store the file decrypted
                $file->storeAs(env('MEDIA_TEMP_FOLDER') . $folder, $filename);

                // Encrypts the file
                $file = $this->encryptFile($request->file('upload_file'));

                // Overwrites the decrypted file with it's encrypted version
                file_put_contents(storage_path('app/' . env('MEDIA_TEMP_FOLDER') . $folder . '/' . $filename), $file);

                // Creates a Temporary File instance and persists it
                return TemporaryFile::create([
                    'folder' => $folder,
                    'filename' => $filename
                ]);
            } else {
                return 'No file uploaded';
            }
        } catch (ValidationException $e) {
            return 'Error: ' . $e->getMessage();
        } catch (\Exception $e) {
            return 'Error: ' . $e;
        }
    }

    /**
     * Encrypts a file according to config/app.php configuration algorithms
     *
     * @param  string $filePath # Path to file to encrypt :
     * @return string # returns an encrypted string of the file
     */
    public function encryptFile(string $filePath): string
    {
        return Crypt::encrypt(file_get_contents($filePath));
    }

    /**
     * Decrypts a file according to config/app.php configuration algorithms
     *
     * @param  string $filePath
     * @return string # returns a decrypted binary of the file
     */
    public function decryptFile(string $filePath): string
    {
        return Crypt::decrypt(file_get_contents($filePath));
    }

    /**
     * Returns first temporary file instance inside a folder
     *
     * @param  string $folder #Folder of the temporary file to get. Doesn't need a path, just the folder
     * @return TemporaryFile
     */
    public function getTemporaryFileByFolder(string $folder): TemporaryFile
    {
        return TemporaryFile::where('folder', $folder)->first();
    }
}
