<?php

namespace App\ApplicationServices\IServices;

interface IDocumentMetaDataService
{
    public function getDocumentMetaDataById(int $id);
}
