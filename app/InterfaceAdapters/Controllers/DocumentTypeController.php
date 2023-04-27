<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IDocumentTypeService;

/**
 * @group DocumentTypes Endpoints
 *
 * DocumentTypes API endpoint
 */
class DocumentTypeController extends Controller
{
    private $service;

    public function __construct(IDocumentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * GET All Document Types
     *
     * Returns a list of all the document types in the system
     *
     * @response 200 scenario="Success" [
     *      {
     *          "id": 90,
     *          "description": "Artigo Cientifico"
     *      },
     *      {
     *          "id": 91,
     *          "description": "Tese"
     *      },
     *      {
     *          "id": 92,
     *          "description": "Outro"
     *      }
     *  ]
     */
    public function getAllDocumentTypes()
    {
        try {
            return $this->service->getAllDocumentTypes();
        } catch (\Exception $exception) {
            return null;
        }
    }
}
