<?php 

namespace App\ApplicationServices\IServices;

use App\Domain\Aggregates\Document\DocumentType;

interface IDocumentTypeService
{
    public function getAllDocumentTypes();
}

?>