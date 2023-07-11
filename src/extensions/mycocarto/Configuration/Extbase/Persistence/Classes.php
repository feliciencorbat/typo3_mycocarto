<?php

declare(strict_types=1);

use Feliciencorbat\Mycocarto\Domain\Model\User;
use Feliciencorbat\Mycocarto\Domain\Model\UserGroup;

return [
    User::class => [
        'tableName' => 'fe_users'
    ],
    UserGroup::class => [
        'tableName' => 'fe_groups'
    ]
];
