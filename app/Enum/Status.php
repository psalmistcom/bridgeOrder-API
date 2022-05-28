<?php

namespace App\Enum;

enum Status: string {
case FAILED = 'failed';
case SUCCESS = 'success';
case PENDING = 'pending';
case APPROVED = 'approved';
case SUSPENDED = 'suspended';
case DECLINED = 'declined';
case EXPIRED = 'expired';
case VERIFIED = 'verified';
case INACTIVE = 'inactive';
case ACTIVE = 'active';
case PAID = 'paid';
case CANCELED = 'canceled';
case ACCEPTED = 'accepted';
case DELIVERED = 'delivered';
case PROCESSING = 'processing';
    }
