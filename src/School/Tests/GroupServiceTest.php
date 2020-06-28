<?php declare(strict_types=1);

namespace School\Tests;

use PHPUnit\Framework\TestCase;
use School\Group;
use School\GroupRepository;
use School\GroupService;
use School\Pupil;
use School\PupilAlreadyInGroupException;
use School\PupilRepository;
use School\TooManyPupilsException;

final class GroupServiceTest extends TestCase
{

    private const GROUP_ID = 123;

    private const PUPIL_ID = 456;

    /**
     * @var GroupService
     */
    private GroupService $SUT;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var PupilRepository
     */
    private $pupilRepository;

    /**
     * @var Group
     */
    private Group $group;

    /**
     * @var Pupil
     */
    private Pupil $pupil;

    protected function setUp() : void
    {
        parent::setUp();
        $this->group = new Group(self::GROUP_ID);
        $this->pupil = new Pupil(self::PUPIL_ID);

        $this->groupRepository = $this->createMock(GroupRepository::class);
        $this->pupilRepository = $this->createMock(PupilRepository::class);

        $this->SUT = new GroupService($this->groupRepository, $this->pupilRepository);
    }

    /**
     * @test
     */
    public function it_should_enlist_pupil_in_group()
    {
        $this->assertEmpty($this->group->getPupils());

        $this->expectThatAGroupIsFound();
        $this->expectThatAPupilIsFound();
        $this->expectThatGroupIsPersisted();

        $this->SUT->enlistPupilInGroup(self::GROUP_ID, self::PUPIL_ID);
        $this->assertEquals([$this->pupil], $this->group->getPupils());
    }

    private function expectThatAGroupIsFound(): void
    {
        $this->groupRepository
          ->expects($this->once())
          ->method('find')
          ->with(self::GROUP_ID)
          ->will($this->returnValue($this->group));
    }

    private function expectThatAPupilIsFound(): void
    {
        $this->pupilRepository
          ->expects($this->once())
          ->method('find')
          ->with(self::PUPIL_ID)
          ->will($this->returnValue($this->pupil));
    }

    private function expectThatGroupIsPersisted(): void
    {
        $this->groupRepository->expects($this->once())
          ->method('persist')
          ->with($this->group);
    }

}
