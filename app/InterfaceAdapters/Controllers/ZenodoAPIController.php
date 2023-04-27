<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IZenodoAPIService;

/**
 * @group Other Undocumented Endpoints
 *
 * These endpoints are incomplete or not properly documented
 */
class ZenodoAPIController extends Controller
{
    private $service;

    public function __construct(IZenodoAPIService $service)
    {
        $this->service = $service;
    }

    public function getDocumentList()
    {
        try {
            return $this->service->getDocumentList();
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getDocumentById(int $id)
    {
        try {
            return $this->service->getDocumentById($id);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
