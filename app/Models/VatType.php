<?php

namespace App\Models;

enum VatType: string
{
    // Philippines VAT rates
    case STANDARD = 'standard'; // 12% standard VAT in Philippines
    case ZERO_RATED = 'zero_rated'; // 0% VAT for specific transactions
    case VAT_EXEMPT = 'vat_exempt'; // VAT-exempt transactions
}
