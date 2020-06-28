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
        $pupilAlreadyInGroup = false;
        foreach ($group->getPupils() as $pupilInGroup) {
            if ($pupilInGroup->getId() == $pupilToBeEnlisted->getId()) {
                $pupilAlreadyInGroup = true;
            }
        }
        if (!$pupilAlreadyInGroup) {
            $group->addPupil($pupilToBeEnlisted);
            $this->groupRepository->persist($group);
        } else {
            throw new PupilAlreadyInGroupException();
        }
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
}
