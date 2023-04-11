<?php

namespace App\InterfaceAdapters\Controllers;

use App\Domain\Aggregates\Document\TemporaryFile;
use Illuminate\Http\Request;

class TemporaryFileController extends Controller
{   
    /** 
     * This controller is saving the file on the request to a temporary location : storage/app/uploads
     * and saving it's meta to a database table "TemporaryFile"
     */
    public function saveTemporaryFile(Request $request) {
        try {
            if ($request->hasFile('upload_file')) {
                $file = $request->file('upload_file');
                $filename = $file->getClientOriginalName();
                $folder = uniqid() . '-' . now()->timestamp;
                //$folder = $request->header('X-CUSTOM-FOLDER');

                $file->storeAs('uploads/tmp/'. $folder, $filename);

                TemporaryFile::create([
                    'folder' => $folder,
                    'filename' => $filename
                ]);

                return $folder;
            } else {
                return 'No file uploaded';
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
