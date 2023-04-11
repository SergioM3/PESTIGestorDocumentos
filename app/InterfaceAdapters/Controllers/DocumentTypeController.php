<?php

namespace App\InterfaceAdapters\Controllers;

use App\ApplicationServices\IServices\IDocumentTypeService;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function __construct(IDocumentTypeService $service) {
        $this->service = $service;
    }

    // GET Controllers
    public function getAllDocumentTypes()
    {
        try {
            return $this->service->getAllDocumentTypes();
        } catch (\Exception $exception) {
            return null;
        }
    }
    
}
