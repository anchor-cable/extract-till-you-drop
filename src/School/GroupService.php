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

        $pupilsInGroup = $group->getPupils();
        $pupilToBeEnlisted = $this->pupilRepository->find($pupilId);
        if (count($pupilsInGroup) < 3) {
            $pupilAlreadyInGroup = false;
            foreach ($pupilsInGroup as $pupilInGroup) {
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
        } else {
            throw new TooManyPupilsException();
        }
    }
}
