<?php

namespace App\Enums;

enum ProjectRole: string
{
    case OWNER = 'owner';

    case ADMIN = 'admin';

    case VIEWER = 'viewer';
}
