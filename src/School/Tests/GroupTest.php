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

final class GroupTest extends TestCase
{

    private const GROUP_ID = 123;

    private const PUPIL_ID = 456;

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
    }

    /**
     * @test
     */
    public function it_should_allow_pupil_in_group()
    {
        $this->assertEmpty($this->group->getPupils());

        $this->group->addPupil($this->pupil);
        $this->assertEquals([$this->pupil], $this->group->getPupils());
    }

    /**
     * @test
     */
    public function it_should_disallow_more_than_three_pupils_in_group()
    {
        $this->group->addPupil(new Pupil(1));
        $this->group->addPupil(new Pupil(2));
        $this->group->addPupil(new Pupil(3));

        $this->expectException(TooManyPupilsException::class);
        $this->group->addPupil($this->pupil);
    }

    /**
     * @test
     */
    public function it_should_disallow_duplicate_pupils()
    {
        $this->group->addPupil($this->pupil);

        $this->expectException(PupilAlreadyInGroupException::class);
        $this->group->addPupil($this->pupil);

    }
}
