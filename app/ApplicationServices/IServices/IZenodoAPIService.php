<?php

namespace App\ApplicationServices\IServices;

use App\ApplicationServices\DTO\DocumentSubmitDTO;

interface IZenodoAPIService
{
    public function getDocumentList();
    public function getDocumentById(int $id);
}
