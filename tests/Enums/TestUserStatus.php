<?php

declare(strict_types=1);

namespace Tests\Enums;

enum TestUserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';
    case Suspended = 'suspended';
}