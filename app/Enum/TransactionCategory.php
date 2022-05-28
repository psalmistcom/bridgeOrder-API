<?php

namespace App\Enum;

enum TransactionCategory: string {
case ORDER = 'order';
case RESERVATION = 'reservation';
case WITHDRAWAL_REQUEST = 'withdrawal request';
    }
