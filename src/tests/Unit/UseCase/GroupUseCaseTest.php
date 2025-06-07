<?php

namespace Tests\Unit\UseCase;

use App\Domain\DTO\FileDTO;
use App\Domain\DTO\ImageResultDTO;
use App\Domain\DTO\Policy\GroupIdDTO;
use App\Domain\Entities\Group;
use App\Domain\Exceptions\FileIsNullException;
use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\Services\Jwt\JwtServiceInterface;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Domain\UseCase\Group\GroupUseCase;
use App\Events\EventType;
use DateTime;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class GroupUseCaseTest extends TestCase
{
    private GroupUseCase $useCase;
    private $fileStorage;
    private $groupRepository;
    private $broadcastService;
    private $authorizeService;
    private $jwtService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fileStorage = $this->createMock(FileStorageInterface::class);
        $this->groupRepository = $this->createMock(GroupRepositoryInterface::class);
        $this->broadcastService = $this->createMock(BroadcastServiceInterface::class);
        $this->authorizeService = $this->createMock(AuthorizeServiceInterface::class);
        $this->jwtService = $this->createMock(JwtServiceInterface::class);

        $this->useCase = new GroupUseCase(
            $this->fileStorage,
            $this->groupRepository,
            $this->broadcastService,
            $this->authorizeService,
            $this->jwtService
        );
    }

    public function testCreateGroupWithoutImage()
    {
        $userId = 1;
        $name = 'Family';
        $fileDTO = null;
        $group = $this->makeGroup();

        $this->groupRepository
            ->expects($this->once())
            ->method('create')
            ->with($userId, $name, null)
            ->willReturn($group);

        $this->broadcastService
            ->expects($this->once())
            ->method('broadcastGroupChanged')
            ->with($group, EventType::Create);

        $result = $this->useCase->create($userId, $name, $fileDTO);

        $this->assertSame($group, $result);
    }

    public function testUploadImageWithValidFile()
    {
        $groupId = 1;
        $fileDTO = new FileDTO('test.jpg', 'image/jpeg', '...binary...');
        $path = 'public/groups/test.jpg';
        $group = $this->makeGroup();

        $this->fileStorage
            ->expects($this->once())
            ->method('storeFile')
            ->with($fileDTO, 'public/groups')
            ->willReturn($path);

        $this->authorizeService
            ->expects($this->once())
            ->method('authorizeGroupOwner')
            ->with(new GroupIdDTO($groupId));

        $this->groupRepository
            ->expects($this->once())
            ->method('uploadImage')
            ->with($groupId, $path)
            ->willReturn($group);

        $this->broadcastService
            ->expects($this->once())
            ->method('broadcastGroupChanged')
            ->with($group, EventType::Update);

        $result = $this->useCase->uploadImage($groupId, $fileDTO);

        $this->assertSame($group, $result);
    }

    public function testUploadImageThrowsIfFileIsNull()
    {
        $this->expectException(FileIsNullException::class);

        $this->useCase->uploadImage(1, null);
    }

    public function testShowImageReturnsImageDTO()
    {
        $groupId = 1;
        $url = 'https://example.com/image.jpg';

        $this->authorizeService
            ->expects($this->once())
            ->method('authorizeGroupMember')
            ->with(new GroupIdDTO($groupId));

        $this->groupRepository
            ->expects($this->once())
            ->method('showImageUrl')
            ->with($groupId)
            ->willReturn($url);

        $result = $this->useCase->showImage($groupId);

        $this->assertInstanceOf(ImageResultDTO::class, $result);
        $this->assertEquals($url, $result->image);
    }

    private function makeGroup(): Group
    {
        return new Group(
            id: 1,
            name: 'Test Group',
            image: null,
            ownerId: 1,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
            inviteLink: null,
            users: [],
            shoppingLists: [],
        );
    }
}
