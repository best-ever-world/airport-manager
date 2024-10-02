<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Api\Model;

class UserGroupModel
{
    public const string VIEW_LIST = 'user:view:list';
    public const string VIEW_ITEM = 'user:view:item';
    public const string CREATE_ITEM = 'user:create:item';
    public const string UPDATE_ITEM = 'user:update:item';
    public const string APPROVE_ITEM = 'user:approve:item';
    public const string DISAPPROVE_ITEM = 'user:disapprove:item';
    public const string ENABLE_ITEM = 'user:enable:item';
    public const string DISABLE_ITEM = 'user:disable:item';
    public const string DELETE_ITEM = 'user:delete:item';
    public const string RESET_PASSWORD = 'user:reset-password';
    public const string UPDATE_PASSWORD = 'user:update-password';
    public const string UPDATE_ROLE = 'user:update-role';
    public const string REGISTER = 'user:register';
}
