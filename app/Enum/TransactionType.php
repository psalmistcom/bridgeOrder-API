<?php

namespace App\Enum;

enum TransactionType: string {
case WALLET_WITHDRAWAL = 'wallet withdrawal';
case WALLET_TOP_UP = 'wallet top up';
case CARD_PAYMENT = 'paid with card.';
    }
