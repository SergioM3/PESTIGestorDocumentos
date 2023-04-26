<?php

namespace App\ApplicationServices\Services;

use App\ApplicationServices\DTO\DocumentDTO;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\DTO\DocumentSubmitDTO;
use App\ApplicationServices\Mappers\DocumentMapper;
use App\ApplicationServices\IServices\IMediaService;
use App\ApplicationServices\IServices\IDocumentService;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;
use App\ApplicationServices\IServices\IDocumentMetadataService;

class DocumentService implements IDocumentService
{
    private $repo;
    private $mapper;
    private $documentMetadataService;
    private $mediaService;

    public function __construct(
        IDocumentRepository $repo,
        DocumentMapper $mapper,
        IDocumentMetadataService $documentMetadataService,
        IMediaService $mediaService
    ) {
        $this->repo = $repo;
        $this->mapper = $mapper;
        $this->documentMetadataService = $documentMetadataService;
        $this->mediaService = $mediaService;
    }

    /**
     * Returns list of internal Documents
     * If user is admin, returns pending too, otherwise returns just published documents
     *
     * @return array
     */
    public function getDocumentsByFilter(): array
    {
        $documentDTOs = [];

        // Get Internal Documents - If user is admin, returns pending too, otherwise returns just published documents
        $documents = Auth::user()->admin == 'Y' ? $this->repo->getDocumentsByFilter() : $this->repo->getPublishedDocumentsByFilter();
        foreach ($documents as $document) {
            $documentDTOs[] = $this->mapper->toListItemDTO($document);
        }

        return $documentDTOs;
    }

    /**
     * Returns Document details (DocumentDTO) of a document, including file binary, by id
     *
     * @param  int $id
     * @return DocumentDTO
     */
    public function getDocumentById(int $id): DocumentDTO
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
     * Returns list of all documents submitted by user
     *
     * @param  int $userId
     * @return array
     */
    public function getDocumentsByUserId(int $userId): array
    {
        $documentDTOs = [];

        $documents = $this->repo->getDocumentsByUserId($userId);
        foreach ($documents as $document) {
            $documentDTOs[] = $this->mapper->toListItemDTO($document);
        }

        return $documentDTOs;
    }

    /**
     * Submits a new document according to documentSubmitDTO request data
     * requires that temporary file gets created first by calling api/temp_file
     *
     * @param  DocumentSubmitDTO $documentSubmitDTO
     * @return string
     */
    public function submitNewDocument(DocumentSubmitDTO $documentSubmitDTO): string
    {
        // Validates the post request
        try {
            // Checks if folder and filename are sent on the request and file exists in temporary folder
            // if not, it's likely a temporary file was not submited first
            request()->temp_document_folder ?? throw new Exception("temp_document_folder missing. Upload a temporary file first with api/temp_file");
            request()->document_filename ?? throw new Exception("document_filename missing. Upload a temporary file first with api/temp_file");
            if (!file_exists(storage_path('app/' . env('MEDIA_TEMP_FOLDER') . request()->temp_document_folder . '/' . request()->document_filename))) {
                throw new Exception("Temporary file missing! Upload a temporary file first with api/temp_file");
            }
            // Adds submit common validation rules
            $this->commonSubmitRules();
        } catch (\Exception $exception) {
            return json_encode(['id' => null, 'message' => $exception->getMessage()]);
        }

        // Sets the default document_state based on the publish_date
        $document_state = ($documentSubmitDTO->publish_date > Carbon::now()) ? 'Pending' : 'Published';

        // Creates a Document instance (not persisted yet)
        $document = new Document([
            'user_id' => Auth::user()->id,
            'document_type_id' => $documentSubmitDTO->document_type_id,
            'publish_date' => $documentSubmitDTO->publish_date,
            'document_state' => $document_state,
            'create_date' => Carbon::now()
        ]);

        // Persists the Document
        $this->repo->insertNewDocument($document);

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
            return json_encode(['id' => null, 'message' => $exception->getMessage()]);
        }

        // Delete temporary file database record. (File was already deleted above)
        $temporaryFile->delete();

        // Delete temporary file folder
        rmdir(storage_path($folder));

        return json_encode(['id' => $document->id, 'message' => 'Submitted']);
    }

    /**
     * Edits document of user, or any document if user is admin
     *
     * @param  array $documentSubmitDTO #JSON of the request mapped as DocumentSubmitDTO
     * @param  int $id # Id of the document to edit
     * @return string
     */
    public function editDocument(array $documentSubmitDTO, int $id): string
    {
        // Gets the document instance from repository
        $document = $this->repo->getDocumentById($id);

        // Validates the request
        try {
            // Throws an exception if the document doesnt exist
            $document ?? throw new Exception("The document you're trying to edit doesn't exist");
            // Throws an exception if the user is not the owner of the document and is not an admin
            (Auth::user()->admin != 'Y' && Auth::user()->id != $document->user_id) ? throw new Exception("You don't have authorization to edit this file") : "";
            // Adds Common rules validation
            $this->commonSubmitRules();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

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

        return "Changes Submitted";
    }

    /**
     * HARD Deletes a document owned by the user, or any if user is admin
     *
     * @param  int $id # Document id of the document to be deleted
     * @return string
     */
    public function deleteDocument(int $id): string
    {
        // Gets the document instance from repository
        $document = $this->repo->getDocumentById($id);

        // Validates the request
        try {
            // Throws an exception if the document doesnt exist
            $document ?? throw new Exception("The document you're trying to delete doesn't exist");
            // Throws an exception if the user is not the owner of the document and is not an admin
            (Auth::user()->admin != 'Y' && Auth::user()->id != $document->user_id) ? throw new Exception("You don't have authorization to delete this file") : "";
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // Commands MetadataService to Delete all the old Metadata to the document
        $this->documentMetadataService->deleteDocumentMetadata($id);

        // Delete uploaded document
        try {
            $document->deleteMedia($document->getFirstMedia()->id);
        } catch (\Exception $exception) {
            //return $exception;
        }

        // Hard deletes document
        $document = $this->repo->deleteDocument($id);

        return "Document Deleted";
    }

    /**
     * Rules commonly applied on submition of document data (new or edit)
     *
     * @return void
     */
    private function commonSubmitRules(): void
    {
        // Gets the count of each document_metadata of the request by metadata_type id
        $countById = array_count_values(array_column(array_column(request()->document_metadata, 'metadata_type'), 'id'));

        // If there's no count with id = 1, means there's no title, and documents must have a title
        $countById[1] ?? throw new Exception("You're document MUST have a title!");

        // If title count > 1 throws an error, because there can be only one title
        $countById[1] > 1 ? throw new Exception("You're document CAN ONLY have ONE title!") : "";

        // If abstract count > 1 throws an error, because there can be only one abstract
        $countById[2] > 1 ? throw new Exception("You're document CAN ONLY have ONE abstract!") : "";

        // ToDo - Add Business rule validations (exemple : publish date can't be shorter then today)
    }
}
