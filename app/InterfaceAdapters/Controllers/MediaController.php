<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use App\ApplicationServices\IServices\IMediaService;

/**
 * @group Document(Internal) Endpoints
 *
 * Document API endpoint
 */
class MediaController extends Controller
{
    private $service;

    public function __construct(IMediaService $service)
    {
        $this->service = $service;
    }

    /**
     * POST Upload Temporary File
     *
     * This endpoint saves a file on the request to a temporary location
     * and persists it's meta to a database table
     *
     * @bodyParam upload_file file required The document to upload on the form-data.
     *
     * @response 201 scenario="Success" {
     *      "folder": "6449ce12a98dc-1682558482",
     *      "filename": "book.pdf",
     *      "updated_at": "2023-04-27T01:21:22.000000Z",
     *      "created_at": "2023-04-27T01:21:22.000000Z",
     *      "id": 78
     * }
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
