<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IDocumentMetadataService;

/**
 * This controller is responsible for retreiving and setting document metadata
 */
class DocumentMetadataController extends Controller
{
    private $service;

    public function __construct(IDocumentMetadataService $service)
    {
        $this->service = $service;
    }

    public function getDocumentMetadataById(int $id)
    {
        try {
            return $this->service->getDocumentMetadataById($id);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
