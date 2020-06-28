# Extract Till You Drop

Code for refactoring talk "Extract Till You Drop" by Mathias Verraes. 
See the screencast at [http://verraes.net/2013/09/extract-till-you-drop/](http://verraes.net/2013/09/extract-till-you-drop/)
(Note that this version of the code has changed since that recording was made.)

I(ganchiku) have did the tutorial in 2013, and wrote an article in blog, and now I have a chance to share my knowledge with a company I work.
This good tutorial has came up to my mind, and I have fixed the code with PHP7.4 and PHPUnit9. Many thanks to the original author, Mathias.

## Kata

You can do this as a kata. The goal is to cover GroupService with tests, and 
refactor it into something much nicer along the way.


## Install

Get [composer](https://getcomposer.org/download/), then run

```
composer install
bin/phpunit
```

Btw, I was once asked what is the difference between mock and stub, and here's a stub one for the test.

```php

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

final class GroupServiceStubTest extends TestCase
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

        $this->groupRepository = new class($this->group) implements GroupRepository {
            private Group $group;
            public function __construct(Group $group)
            {
                $this->group = $group;
            }
            public function find(int $id): Group
            {
                return $this->group;
            }
            public function persist(Group $group): void
            {
            }
        };
        $this->pupilRepository = new class($this->pupil) implements PupilRepository {
            private Pupil $pupil;
            public function __construct(Pupil $pupil)
            {
                $this->pupil = $pupil;
            }
            public function find(int $id): Pupil
            {
                return $this->pupil;
            }
            public function persist(Pupil $pupil): void
            {
            }
        };
        $this->SUT = new GroupService($this->groupRepository, $this->pupilRepository);
    }

    /**
     * @test
     */
    public function it_should_enlist_pupil_in_group()
    {
        $this->assertEmpty($this->group->getPupils());

        $this->SUT->enlistPupilInGroup(self::GROUP_ID, self::PUPIL_ID);
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
        $this->SUT->enlistPupilInGroup(self::GROUP_ID, self::PUPIL_ID);
    }

    /**
     * @test
     */
    public function it_should_disallow_duplicate_pupils()
    {
        $this->group->addPupil($this->pupil);

        $this->expectException(PupilAlreadyInGroupException::class);
        $this->SUT->enlistPupilInGroup(self::GROUP_ID, self::PUPIL_ID);

    }
}

```

You can run the test, but you cannot assure the if the method `persist` has called. You see the tests still pass when you remove the method, `persist`, from GroupService.php.

See: https://martinfowler.com/articles/mocksArentStubs.html
