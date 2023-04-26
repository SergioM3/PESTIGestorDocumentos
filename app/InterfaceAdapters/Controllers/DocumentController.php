<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\IServices\IDocumentService;

/**
 * This controller is responsible for retreiving and setting document metadata
 */
class DocumentController extends Controller
{
    private $service;

    public function __construct(IDocumentService $service)
    {
        $this->service = $service;
    }

    public function getDocumentsByUserId(int $userId)
    {
        try {
            return $this->service->getDocumentsByUserId($userId);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getDocumentsByFilter()
    {
        try {
            return $this->service->getDocumentsByFilter();
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

    public function submitNewDocument(Request $request)
    {
        try {
            // This guarantees that the request will be parsed to contain all and only the fields necessary to build the DTO
            $documentSubmitDTO = new DocumentSubmitDTO(...$request->only(DocumentSubmitDTO::getPublicParams()));
        } catch (\Error $exception) {
            return $exception;
        }

        try {
            return $this->service->submitNewDocument($documentSubmitDTO);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function editDocument(Request $request, int $id)
    {
        try {
            $documentSubmitDTO = $request->only(DocumentSubmitDTO::getPublicParams());
        } catch (\Error $exception) {
            return $exception->getMessage();
        }

        try {
            return $this->service->editDocument($documentSubmitDTO, $id);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function deleteDocument(int $id)
    {
        try {
            return $this->service->deleteDocument($id);
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
