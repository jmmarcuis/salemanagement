<?php

namespace App\Enums;

enum VatType: string
{
    case STANDARD = 'standard';
    case ZERO_RATED = 'zero_rated';
    case VAT_EXEMPT = 'vat_exempt';
}