<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IDocumentMetadataService;

/**
 * @group DocumentMetadata Endpoints
 *
 * DocumentMetadata API endpoint
 */
class DocumentMetadataController extends Controller
{
    private $service;

    public function __construct(IDocumentMetadataService $service)
    {
        $this->service = $service;
    }

    /**
     * GET DocumentMetadata
     *
     * Returns array of meta data objects of a document by it's id
     *
     * @urlParam id integer required The id of the document. Example: 6
     *
     * @response 200 scenario="Success" [
     *      {
     *          "id": 90,
     *          "value": "quis",
     *          "metadata_type": {
     *              "id": 2,
     *              "description": "Abstract"
     *          }
     *      },
     *      {
     *          "id": 91,
     *          "value": "keyword2",
     *          "metadata_type": {
     *              "id": 3,
     *              "description": "Keywords"
     *          }
     *      },
     *      {
     *          "id": 92,
     *          "value": "checkTitulo1",
     *          "metadata_type": {
     *              "id": 1,
     *              "description": "Titulo"
     *          }
     *      }
     *  ]
     */
    public function getDocumentMetadataById(int $id)
    {
        try {
            return $this->service->getDocumentMetadataById($id);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
