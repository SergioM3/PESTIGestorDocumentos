<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\IServices\IDocumentService;

/**
 * @group Document(Internal) Endpoints
 *
 * Document API endpoint
 */
class DocumentController extends Controller
{
    private $service;

    public function __construct(IDocumentService $service)
    {
        $this->service = $service;
    }

    /**
     * GET Documents by User
     *
     * Gets document list of all documents owned by a specified user
     *
     * @urlParam userId integer required The id of the user. Example: 6
     * @queryParam title string Filters documents by title. Example: Titulo 1
     * @queryParam abstract string Filters documents by abstract. Example: Abstract 1
     * @queryParam document_state string Filters documents by it's state (Accepted Published and Pending only). Example: Published
     * @queryParam document_type string Filters documents by it's type. Example: Artigo Cientifico
     * @queryParam publish_date_from datetime Filters to return only documents published after this date. Example: 2020-12-22
     * @queryParam publish_date_to datetime Filters to return only documents published before this date. Example: 2026-12-22
     * @queryParam page integer Pagination -> number of the page to see. Example: 1
     * @queryParam page_size integer Pagination -> number of documents to see per page. Example: 10
     * @queryParam orderBy string Sorting -> Field by which documents are sorted. Example: source
     * @queryParam orderDir integer Sorting -> Sorting direction. Example: asc
     *
     * @response 200 scenario="Success" {
     *   "id": 35,
     *  "user_id": 6,
     *    "document_state": "Pending",
     *  "publish_date": "2025-12-22 18:00:11",
     *  "document_type": "Artigo Cientifico",
     *  "document_title": "Titulo de documento",
     *  "doi": null,
     *  "url": null,
     *  "source": "Internal",
     *  "document_abstract": "quis",
     *  "document_keywords": [
     *      "laudantium"
     *  ],
     *  "document_authors": []
     *  }
     */
    public function getDocumentsByUserId(int $userId)
    {
        try {
            return $this->service->getDocumentsByUserId($userId);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * GET Documents List
     *
     * Returns a document list of all documents if user is admin, otherwise just published documents
     *
     * @urlParam userId integer required The id of the user. Example: 6
     * @queryParam title string Filters documents by title. Example: Titulo 1
     * @queryParam abstract string Filters documents by abstract. Example: Abstract 1
     * @queryParam document_state string Filters documents by it's state (Accepted Published and Pending only). Example: Published
     * @queryParam document_type string Filters documents by it's type. Example: Artigo Cientifico
     * @queryParam publish_date_from datetime Filters to return only documents published after this date. Example: 2020-12-22
     * @queryParam publish_date_to datetime Filters to return only documents published before this date. Example: 2026-12-22
     * @queryParam page integer Pagination -> number of the page to see. Example: 1
     * @queryParam page_size integer Pagination -> number of documents to see per page. Example: 10
     * @queryParam orderBy string Sorting -> Field by which documents are sorted. Example: source
     * @queryParam orderDir integer Sorting -> Sorting direction. Example: asc
     *
     * @response 200 scenario="Success" {
     *   "id": 35,
     *  "user_id": 6,
     *    "document_state": "Pending",
     *  "publish_date": "2025-12-22 18:00:11",
     *  "document_type": "Artigo Cientifico",
     *  "document_title": "Titulo de documento",
     *  "doi": null,
     *  "url": null,
     *  "source": "Internal",
     *  "document_abstract": "quis",
     *  "document_keywords": [
     *      "laudantium"
     *  ],
     *  "document_authors": []
     *  }
     */
    public function getDocumentsByFilter()
    {
        try {
            return $this->service->getDocumentsByFilter();
        } catch (\Exception $exception) {
            return $exception;
        }
    }
    /**
     * GET Documents By id
     *
     * Returns document details by it's id
     *
     * @urlParam id integer required The id of the document. Example: 6
     *
     * @response 200 scenario="Success" {
     *      "id": 53,
     *      "user_id": 6,
     *      "document_state": "Pending",
     *      "publish_date": "2025-12-22 18:00:11",
     *      "create_date": "2023-04-26 14:25:04",
     *      "update_date": null,
     *      "delete_date": null,
     *      "document_type": {
     *          "id": 1,
     *          "description": "Artigo Cientifico"
     *      },
     *      "document_metadata": [
     *          {
     *              "id": 355,
     *              "value": "quis",
     *              "metadata_type": {
     *                  "id": 1,
     *                  "description": "Titulo"
     *              }
     *          },
     *          {
     *              "id": 356,
     *              "value": "laudantium",
     *              "metadata_type": {
     *                  "id": 2,
     *                  "description": "Abstract"
     *              }
     *          }
     *      ],
     *      "document_media": {
     *          "id": 67,
     *          "model_type": "App\\Domain\\Aggregates\\Document\\Document",
     *          "model_id": 53,
     *          "uuid": "c6dd8e46-e8c6-4041-bc7f-cf86dcc7d6be",
     *          "collection_name": "default",
     *          "name": "book",
     *          "file_name": "book.pdf",
     *          "mime_type": "text/plain",
     *          "disk": "media",
     *          "conversions_disk": "media",
     *          "size": 407608,
     *          "manipulations": [],
     *          "custom_properties": [],
     *          "generated_conversions": [],
     *          "responsive_images": [],
     *          "order_column": 1,
     *          "created_at": "2023-04-26T14:25:04.000000Z",
     *          "updated_at": "2023-04-26T14:25:04.000000Z",
     *          "original_url": "/storage/67/book.pdf",
     *          "preview_url": ""
     *      },
     *      "document_file":
     *          "JVBERi0xLjINJeLjz9MNCjY2IDAgb2JqDTw8I
     *           MyBdIA0vTCAyMjkxNTYgDS9FIDIwMzU3IA0vT
     *           ICAgICAgICAgICAgICAgICAgICAgICAgICAgI
     *           MDAwMDE2IDAwMDAwIG4NCjAwMDAwMDExNDggM
     *           OTU3IDAwMDAwIG4NCjAwMDAwMDIxNjYgMDAwM
     *           IDAwMDAwIG4NCjAwMDAwMDM5MDAgMDAwMDAgb
     *           MDAwIG4NCjAwMDAwMDUwOTkgMDAwMDAgbg0=="
     * }
     */
    public function getDocumentById(int $id)
    {
        try {
            return $this->service->getDocumentById($id);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * POST Submit Document
     *
     * Submits document.
     * MUST first upload temporary file with api/temp_file
     *
     * @queryParam temp_document_folder string required Sub-folder within temp folder where the file was temporarily uploaded to. Example: 6449343e3d09e-1682519102
     * @queryParam document_filename string required File name of the temporary file uploaded. Example: document.pdf
     *
     * @bodyParam document_type_id integer required ID of the document (Full list of ids can be retreived with api/document_types). Example: 1
     * @bodyParam publish_date datetime required Publish date of the document. Example: 2025-12-22 18:00:11
     * @bodyParam document_metadata object[] required Document metadata. MUST HAVE AT LEAST A TITLE (id=1) Example: [{"value": "quis","metadata_type": {"id": 1}},{"value": "laudantium","metadata_type": {"id": 2}}]
     *
     * @response 400 scenario="Temp folder missing" {
     *      "error": "temp_document_folder missing. Upload a temporary file first with api/temp_file"
     * }
     *
     * @response 400 scenario="Temp filename missing" {
     *      "error": "document_filename missing. Upload a temporary file first with api/temp_file"
     * }
     *
     * @response 400 scenario="Temp file given not in temp foder" {
     *      "error": "Temporary file missing! Upload a temporary file first with api/temp_file"
     * }
     *
     * @response 400 scenario="No title" {
     *      "error": "You're document MUST have a title!"
     * }
     *
     * @response 400 scenario="Multiple titles" {
     *      "error": "You're document CAN ONLY have ONE title!"
     * }
     *
     * @response 400 scenario="Multiple Abstracts" {
     *      "error": "You're document CAN ONLY have ONE abstract!"
     * }
     *
     * @response 201 scenario="Success" {
     *      "id" : 302
     *      "message" : "Document Submited"
     * }
     */
    public function submitNewDocument(Request $request)
    {
        try {
            // This guarantees that the request will be parsed to contain all and only the fields necessary to build the DTO
            $documentSubmitDTO = new DocumentSubmitDTO(...$request->only(DocumentSubmitDTO::getPublicParams()));
        } catch (\Error $exception) {
            return $exception;
        }

        try {
            return response()->json(['id' => $this->service->submitNewDocument($documentSubmitDTO), 'message' => 'Document Submitted'], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }

    /**
     * PUT Edit Document
     *
     * Edits document.
     *
     * @urlParam id integer required The id of the document. Example: 6
     * @bodyParam document_type_id integer ID of the document (Full list of ids can be retreived with api/document_types). Example: 1
     * @bodyParam publish_date datetime Publish date of the document. Example: 2025-12-22 18:00:11
     * @bodyParam document_metadata object[] Document metadata. MUST HAVE AT LEAST A TITLE (id=1) Example: [{"value": "quis","metadata_type": {"id": 1}},{"value": "laudantium","metadata_type": {"id": 2}}]
     *
     * @response 400 scenario="Document ID doesnt exist" {
     *      "error": "The document you're trying to edit doesn't exist"
     * }
     *
     * @response 403 scenario="Not document owner nor Admin" {
     *      "error": "You don't have authorization to edit this file"
     * }
     *
     * @response 400 scenario="No title" {
     *      "error": "You're document MUST have a title!"
     * }
     *
     * @response 400 scenario="Multiple titles" {
     *      "error": "You're document CAN ONLY have ONE title!"
     * }
     *
     * @response 400 scenario="Multiple Abstracts" {
     *      "error": "You're document CAN ONLY have ONE abstract!"
     * }
     *
     * @response 201 scenario="Success" {
     *      "message" : "Changes Submited"
     * }
     */
    public function editDocument(Request $request, int $id)
    {
        try {
            $documentSubmitDTO = $request->only(DocumentSubmitDTO::getPublicParams());
        } catch (\Error $exception) {
            return $exception->getMessage();
        }

        try {
            return response()->json(['message' => $this->service->editDocument($documentSubmitDTO, $id)], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
    /**
     * DELETE Document
     *
     * HARD deletes document.
     * DO NOT USE if not certain.
     *
     * @urlParam id integer required The id of the document. Example: 6
     * @response 400 scenario="Document ID doesnt exist" {
     *      "error": "The document you're trying to delete doesn't exist"
     * }
     *
     * @response 403 scenario="Not document owner nor Admin" {
     *      "error": "You don't have authorization to delete this file"
     * }
     *
     *
     * @response 201 scenario="Success" {
     *      "message" : "Document Deleted"
     * }
     */
    public function deleteDocument(int $id)
    {
        try {
            return response()->json(['message' => $this->service->deleteDocument($id)], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}
