<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use App\ApplicationServices\IServices\IMediaService;

class MediaController extends Controller
{
    private $service;

    public function __construct(IMediaService $service)
    {
        $this->service = $service;
    }
    /**
     * This controller funtion is saving the file on the request to a temporary location : storage/app/uploads
     * and saving it's meta to a database table "TemporaryFile"
     */
    public function saveTemporaryFile(Request $request)
    {
        try {
            return $this->service->saveTemporaryFile($request);
        } catch (\Exception $e) {
            return 'Error: ' . $e;
        }
    }
}
