<?php

namespace Feliciencorbat\Mycocarto\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class User extends AbstractEntity
{
    protected string $name = "";

    /**
     * @var ObjectStorage<UserGroup>
     */
    protected ObjectStorage $usergroup;


    public function __construct()
    {
        $this->usergroup = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->usergroup = $this->usergroup ?? new ObjectStorage();
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return ObjectStorage<UserGroup>
     */
    public function getUsergroup(): ObjectStorage
    {
        return $this->usergroup;
    }

    /**
     * @param UserGroup $userGroup
     * @return void
     */
    public function addUserGroup(UserGroup $userGroup): void
    {
        $this->usergroup->attach($userGroup);
    }

    /**
     * @param UserGroup $userGroup
     * @return void
     */
    public function removeUserGroup(UserGroup $userGroup): void
    {
        $this->usergroup->detach($userGroup);
    }

    /**
     * @param ObjectStorage<UserGroup> $usergroup
     */
    public function setUsergroup(ObjectStorage $usergroup): void
    {
        $this->usergroup = $usergroup;
    }


}