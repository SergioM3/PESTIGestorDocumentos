<?php

namespace App\InterfaceAdapters\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\Type\VoidType;
use App\Domain\Aggregates\Document\Document;
use App\Domain\Aggregates\Metadata\DocumentMetaData;
use App\ApplicationServices\IServices\IDocumentMetaDataService;

/**
 * This controller is responsible for retreiving and setting document metadata
 */
class DocumentMetaDataController extends Controller
{
    private $service;

    public function __construct(IDocumentMetaDataService $service)
    {
        $this->service = $service;
    }

    public function getDocumentMetaDataById(int $id): array
    {
        try {
            return $this->service->getDocumentMetaDataById($id);
        } catch (\Exception $exception) {
            return null;
        }
    }
}
