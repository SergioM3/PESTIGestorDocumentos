<?php

namespace App\InterfaceAdapters\IRepositories;

interface IDocumentMetaDataRepository
{
    public function getDocumentMetaDataById(int $id);
}
