<?php

namespace App\Constants;

use ReflectionClass;

class TransactionStatus
{
    public const IN_ANALYSIS = 1; // Pagamento em análise - anti-fraude
    public const PAID = 2; // PAGO
    public const REFUNDED = 3; // Estornado
    public const CANCELED = 4; // Cancelado
}

