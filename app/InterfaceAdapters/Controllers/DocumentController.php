<?php

namespace App\InterfaceAdapters\Controllers;

use ReflectionClass;
use ReflectionProperty;
use Illuminate\Http\Request;
use SebastianBergmann\Type\VoidType;
use App\Domain\Aggregates\Document\Document;
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

    public function getDocumentList(Request $request)
    {
        try {
            return $this->service->getDocumentList(
                $request->query('sort_by') ?? null,
                $request->query('sort_dir') ?? null,
                $request->query('docs_per_page') ?? null,
                $request->query('page_number') ?? null
            );
        } catch (\Exception $exception) {
            return $exception;
        }
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
            $documentSubmitDTO = new DocumentSubmitDTO(...$request->only(DocumentSubmitDTO::getPublicParams()));
        } catch (\Error $exception) {
            return $exception->getMessage();
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
