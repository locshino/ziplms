<?php

namespace App\Enums;

enum QueueName: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case DEFAULT = 'default';
}
