<?php

namespace App\Models;

enum SaleStatus: string
{
    case PENDING = 'pending';
    case COMPLETE = 'complete';
    case INCOMPLETE = 'incomplete';
    case CANCELED = 'canceled';
}


