<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\Type\VoidType;
use App\Domain\Aggregates\Document\Document;
use App\Domain\Aggregates\Metadata\DocumentMetadata;
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
        //return DocumentMetadata::with('metadataType')->where('document_id', $id)->get();
        try {
            return $this->service->getDocumentMetadataById($id);
        } catch (\Exception $exception) {
            return null;
        }

    }
}
