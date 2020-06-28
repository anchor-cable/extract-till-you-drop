<?php declare(strict_types=1);

namespace School;

class Pupil
{
    /**
     * @var int
     */
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }


    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}
