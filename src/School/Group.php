<?php declare(strict_types=1);

namespace School;

class Group
{
    private int $id;

    /**
     * @var Pupil[]
     */
    private array $pupils = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return Pupil[]
     */
    public function getPupils() : array
    {
        return $this->pupils;
    }

    public function addPupil(Pupil $pupil) : void
    {
        $this->guardAgainstTooManyPupils();
        $this->guardAgainstDuplicatePupils($pupil);

        $this->pupils[] = $pupil;
    }

    /**
     * @throws TooManyPupilsException
     */
    private function guardAgainstTooManyPupils(): void
    {
        if (count($this->getPupils()) >= 3) {
            throw new TooManyPupilsException();
        }
    }

    /**
     * @param Pupil $pupil
     * @throws PupilAlreadyInGroupException
     */
    private function guardAgainstDuplicatePupils(Pupil $pupil): void
    {
        $pupilAlreadyInGroup = false;
        foreach ($this->getPupils() as $pupilInGroup) {
            if ($pupilInGroup->getId() == $pupil->getId()) {
                $pupilAlreadyInGroup = true;
            }
        }
        if ($pupilAlreadyInGroup) {
            throw new PupilAlreadyInGroupException();
        }
    }
}
