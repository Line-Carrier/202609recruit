<?php

namespace App\Enum;

enum OrderStatusEnum: int
{
    case DRAFT = -1;
    case PENDING = 0;
    case PROCESSING = 1;
    case COMPLETED = 2;
    case CANCELLED = 3;
}