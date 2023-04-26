<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IZenodoAPIService;

/**
 * This controller is responsible for retreiving and setting document metadata
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
