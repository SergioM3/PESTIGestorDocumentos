<?php

namespace Tests\Unit;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Auth;
use App\Domain\Aggregates\Document\Document;
use App\ApplicationServices\Mappers\DocumentMapper;
use App\ApplicationServices\DTO\DocumentListItemDTO;
use App\ApplicationServices\IServices\IMediaService;
use App\ApplicationServices\Services\DocumentService;
use App\InterfaceAdapters\IRepositories\IDocumentRepository;
use App\ApplicationServices\IServices\IDocumentMetadataService;

class DocumentServiceTest extends TestCase
{
    private $repoMock;
    private $documentTypeMock;
    private $documentMetadataMock;
    private $documentMock;
    private $mapperMock;
    private $documentMetadataServiceMock;
    private $mediaServiceMock;
    private $userMock;

    protected function setUp(): void
    {
        $this->userMock = Mockery::mock(User::class);
        Auth::shouldReceive('user')->andReturn($this->userMock);

        $this->documentTypeMock = Mockery::mock('overload:App\\Domain\\Aggregates\\Document\\DocumentType');
        $this->documentTypeMock->id = 1;
        $this->documentTypeMock->description = "Artigo Cientifico";


        $this->documentMetadataMock = Mockery::mock('overload:App\\Domain\\Aggregates\\Metadata\\DocumentMetadata');
        $metadataTypeMock = Mockery::mock('overload:App\\Domain\\Aggregates\\Metadata\\MetadataType');
        $this->documentMetadataMock->metadata_type = $metadataTypeMock;
        $this->documentMetadataMock->metadata_type->id = 1;
        $this->documentMetadataMock->metadata_type->description = 'Titulo';
        $this->documentMetadataMock->id = 20;
        $this->documentMetadataMock->value = "title1";

        $this->documentMock = Mockery::mock('overload:App\\Domain\\Aggregates\\Document\\Document');
        $this->documentMock->user_id = 1;
        $this->documentMock->id = 1;
        $this->documentMock->user_id = 1;
        $this->documentMock->documentType = $this->documentTypeMock;
        $this->documentMock->publish_date = '2021-12-22 18:00:11';
        $this->documentMock->document_state = "Published";
        $this->documentMock->create_date = '2022-12-22 11:00:11';
        $this->documentMock->documentMetadata = [$this->documentMetadataMock];

        $this->repoMock = Mockery::mock(IDocumentRepository::class);
        /*$this->repoMock = Mockery::mock(IDocumentRepository::class, function (MockInterface $mock) {
            $mock->shouldReceive('getPublishedDocumentsByFilter')->andReturn([$this->documentMock]);
            $mock->shouldReceive('getDocumentsByFilter')->andReturn([$this->documentMock]);
        });*/

        $this->mapperMock = Mockery::mock(DocumentMapper::class);
        $this->documentMetadataServiceMock = Mockery::mock(IDocumentMetadataService::class);
        $this->mediaServiceMock = Mockery::mock(IMediaService::class);
    }


    public function testGetDocumentsByFilter(): void
    {
        // Given
        $documentService = new DocumentService($this->repoMock, new DocumentMapper(), $this->documentMetadataServiceMock, $this->mediaServiceMock);
        $isAdmin = false; // User is not an admin
        $this->userMock->admin = $isAdmin ? 'Y' : 'N';

        // Function should use getDocumentsByFilter if user is admin or getPublishedDocumentsByFilter if not and return stub
        $this->repoMock->shouldReceive($isAdmin ? 'getDocumentsByFilter' : 'getPublishedDocumentsByFilter')->once()->andReturn([$this->documentMock]);

        // When
        $result = $documentService->getDocumentsByFilter();

        // Then
        $expected = new DocumentListItemDTO(1, 1, '2021-12-22 18:00:11', $this->documentTypeMock, null, null, "Internal", $this->documentMetadataMock->value, null, [], []);
        dd($result);
        $this->assertEquals([$expected], $result);
    }
}
