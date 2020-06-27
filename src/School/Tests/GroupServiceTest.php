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

    /**
     * @test
     */
    public function it_should_()
    {
        $groupRepository = $this->getMockBuilder('School\GroupRepository')
          ->getMock();
        $pupilRepository = $this->getMockBuilder('School\PupilRepository')
          ->getMock();

        $group = new GroupService($groupRepository, $pupilRepository);

        $group->add(123, 234);

        $this->assertTrue(true);
    }

}
