<?php

namespace App\Enums\Chat;

enum MessageType: string
{
    case TEXT = 'text';
    case ATTACHMENT = 'attachment';

}
