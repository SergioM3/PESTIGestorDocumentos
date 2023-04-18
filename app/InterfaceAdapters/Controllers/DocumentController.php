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

    public function getAllDocuments()
    {
        try {
            return $this->service->getAllDocuments();
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
}
