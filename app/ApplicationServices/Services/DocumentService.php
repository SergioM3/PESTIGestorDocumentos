<?php

namespace App\ApplicationServices\Services;

use Exception;
use Carbon\Carbon;
use App\Domain\Aggregates\Document\Document;
use App\Domain\Aggregates\Document\TemporaryFile;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\Mappers\DocumentMapper;
use App\ApplicationServices\IServices\IMediaService;
use App\ApplicationServices\IServices\IDocumentService;
use App\ApplicationServices\IServices\IZenodoAPIService;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;
use App\ApplicationServices\IServices\IDocumentMetadataService;

class DocumentService implements IDocumentService
{
    private $repo;
    private $mapper;
    private $documentMetadataService;
    private $mediaService;
    private $zenodoAPIService;

    public function __construct(
        IDocumentRepository $repo,
        DocumentMapper $mapper,
        IDocumentMetadataService $documentMetadataService,
        IMediaService $mediaService,
        IZenodoAPIService $zenodoAPIService
    ) {
        $this->repo = $repo;
        $this->mapper = $mapper;
        $this->documentMetadataService = $documentMetadataService;
        $this->mediaService = $mediaService;
        $this->zenodoAPIService = $zenodoAPIService;
    }

    public function getDocumentList()
    {
        $documentDTOs = [];

        // Get Internal Documents
        $documents = $this->repo->getAllDocuments();
        foreach ($documents as $document) {
            $documentDTOs[] = $this->mapper->toListItemDTO($document);
        }

        // Get Zenodo Documents
        $zenodoDocuments = $this->zenodoAPIService->getDocumentList();
        foreach ($zenodoDocuments as $document) {
            $documentDTOs[] = $document;
        }

        return $documentDTOs;
    }

    public function getDocumentById(int $id)
    {
        $document = $this->repo->getDocumentById($id);
        $documentDTO = $this->mapper->toDTO($document);

        // Decrtypt file and encodes it to base64 for transfer in JSON
        $file = base64_encode($this->mediaService->decryptFile($documentDTO->document_media->getPath()));

        // Adds it to DTO
        $documentDTO->document_file = $file;

        return $documentDTO;
    }

    /**
     * Submits a new document according to documentSubmitDTO request data
     * requires that temporary file gets created first
     *
     * @param  DocumentSubmitDTO $documentSubmitDTO
     * @return DocumentDTO
     */
    public function submitNewDocument(DocumentSubmitDTO $documentSubmitDTO)
    {
        // Validates the post request
        try {
            // Checks if folder and filename are sent on the request, if not, it's likely a temporary file was not submited first
            request()->temp_document_folder == null ? throw new Exception("temp_document_folder missing. Upload a temporary file first with api/temp_file") : "";
            request()->document_filename == null ? throw new Exception("document_filename missing. Upload a temporary file first with api/temp_file") : "";

            // ToDo - Add Business rule validations (exemple : publish date can't be shorter then today)
            /*request()->validate([
                'temp_document_folder' => 'required',
                'document_filename' => 'required'
            ]);*/
        } catch (\Exception $exception) {
            return $exception;
        }

        // Sets the default document_state based on the publish_date
        $document_state = ($documentSubmitDTO->publish_date > Carbon::now()) ? 'Pending' : 'Published';

        // Creates a Document instance (not persisted yet)
        $document = new Document([
            'user_id' => $documentSubmitDTO->user_id,
            'document_type_id' => $documentSubmitDTO->document_type_id,
            'publish_date' => $documentSubmitDTO->publish_date,
            'document_state' => $document_state,
            'create_date' => Carbon::now()
        ]);

        // Persists the Document
        $document = $this->repo->insertNewDocument($document);

        // Commands MetadataService to Add Document Metadata to the document, in line with meta data in the post request
        $this->documentMetadataService->insertDocumentMetadata($documentSubmitDTO->document_metadata, $document->id);

        // Gets temporary file instance from mediaService
        $temporaryFile = $this->mediaService->getTemporaryFileByFolder($documentSubmitDTO->temp_document_folder);

        // Sets folder and path where temporary file is
        $folder = 'app/' . env('MEDIA_TEMP_FOLDER') . $documentSubmitDTO->temp_document_folder;
        $path = storage_path($folder . '/' . $documentSubmitDTO->document_filename);

        /*
            Uploads Media with Spatie Media Library Package:
             This will move the file from it's temporary path to it's path set in variable $path
             and persist the file's metadata to table "media"
        */
        try {
            $document->addMedia($path)->toMediaCollection();
        } catch (\Exception $exception) {
            return $exception;
        }

        // Delete temporary file database record. (File was already deleted above)
        $temporaryFile->delete();

        // Delete temporary file folder
        rmdir(storage_path($folder));

        // Returns documentSubmitDTO
        return $this->mapper->toDTO($document); // To Change this to ->toSubmitDTO ASAP (throwing an error, thus toDTO used instead)
    }

    public function editDocument($documentSubmitDTO, $id)
    {
        // Validates the post request
        try {
            // Checks if folder and filename are sent on the request, if not, it's likely a temporary file was not submited first
            //request()->temp_document_folder == null ? throw new Exception("temp_document_folder missing. Upload a temporary file first with api/temp_file") : "";
            //request()->document_filename == null ? throw new Exception("document_filename missing. Upload a temporary file first with api/temp_file") : "";

            // ToDo - Add Business rule validations (exemple : publish date can't be shorter then today)
            /*request()->validate([
                'temp_document_folder' => 'required',
                'document_filename' => 'required'
            ]);*/
        } catch (\Exception $exception) {
            return $exception;
        }


        // Gets the document instance from repository
        $document = $this->repo->getDocumentById($id);

        // Persists the Changes to the document Document
        $document = $this->repo->editDocument($documentSubmitDTO, $document);

        // Commands MetadataService to Delete all the old Metadata to the document
        $this->documentMetadataService->deleteDocumentMetadata($document->id);

        // Commands MetadataService to Add the new Metadata to the document, in line with meta data in the post request
        if (isset($documentSubmitDTO['document_metadata'])) {
            $this->documentMetadataService->insertDocumentMetadata($documentSubmitDTO["document_metadata"], $document->id);
        }

        // If temp_document_foder and document_filename are passed as parameters delete the document and replace it with that temporary file
        if (isset($documentSubmitDTO['temp_document_folder']) && isset($documentSubmitDTO['document_filename'])) {
            // Delete uploaded document
            try {
                $document->deleteMedia($document->getFirstMedia()->id);
            } catch (\Exception $exception) {
                return $exception;
            }

            // Gets temporary file instance from mediaService
            $temporaryFile = $this->mediaService->getTemporaryFileByFolder($documentSubmitDTO['temp_document_folder']);

            // Sets folder and path where temporary file is
            $folder = 'app/' . env('MEDIA_TEMP_FOLDER') . $documentSubmitDTO['temp_document_folder'];
            $path = storage_path($folder . '/' . $documentSubmitDTO['document_filename']);

            /*
                Uploads Media with Spatie Media Library Package:
                This will move the file from it's temporary path to it's path set in variable $path
                and persist the file's metadata to table "media"
            */
            try {
                $document->addMedia($path)->toMediaCollection();
            } catch (\Exception $exception) {
                return $exception;
            }

            // Delete temporary file database record. (File was already deleted above)
            $temporaryFile->delete();

            // Delete temporary file folder
            rmdir(storage_path($folder));
        }

        // Returns documentSubmitDTO
        return $this->mapper->toDTO($document); // To Change this to ->toSubmitDTO ASAP (throwing an error, thus toDTO used instead)
    }

    public function deleteDocument($id)
    {
        // Gets the document instance from repository
        $document = $this->repo->getDocumentById($id);

        // Commands MetadataService to Delete all the old Metadata to the document
        $this->documentMetadataService->deleteDocumentMetadata($id);

        // Delete uploaded document
        try {
            $document->deleteMedia($document->getFirstMedia()->id);
        } catch (\Exception $exception) {
            return $exception;
        }

        // Hard deletes document
        $document = $this->repo->deleteDocument($id);

        return "Document Deleted";
    }
}
