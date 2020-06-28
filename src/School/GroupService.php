<?php declare(strict_types=1);

namespace School;

class GroupService
{
    /** @var GroupRepository */
    private GroupRepository $groupRepository;

    /** @var PupilRepository */
    private PupilRepository $pupilRepository;

    public function __construct(GroupRepository $groupRepository, PupilRepository $pupilRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->pupilRepository = $pupilRepository;
    }

    public function enlistPupilInGroup($groupId, $pupilId)
    {
        $group = $this->groupRepository->find($groupId);

        $pupilToBeEnlisted = $this->pupilRepository->find($pupilId);

        $this->guardAgainstTooManyPupils($group);
        $this->guardAgainstDuplicatePupils($group, $pupilToBeEnlisted);
        
        $group->addPupil($pupilToBeEnlisted);
        $this->groupRepository->persist($group);
    }

    /**
     * @param Group $group
     * @throws TooManyPupilsException
     */
    private function guardAgainstTooManyPupils(Group $group): void
    {
        if (count($group->getPupils()) >= 3) {
            throw new TooManyPupilsException();
        }
    }

    /**
     * @param Group $group
     * @param Pupil $pupilToBeEnlisted
     * @throws PupilAlreadyInGroupException
     */
    private function guardAgainstDuplicatePupils(Group $group, Pupil $pupilToBeEnlisted): void
    {
        $pupilAlreadyInGroup = false;
        foreach ($group->getPupils() as $pupilInGroup) {
            if ($pupilInGroup->getId() == $pupilToBeEnlisted->getId()) {
                $pupilAlreadyInGroup = true;
            }
        }
        if ($pupilAlreadyInGroup) {
            throw new PupilAlreadyInGroupException();
        }
    }
}
