<?php

namespace App\Enum;

enum ActivityType: string
{
case USER_SIGN_UP = 'user signup';
case USER_SIGN_IN = 'user sign in';
case USER_PASSWORD_CHANGE = 'user password change';
case USER_PASSWORD_RESET = 'user password reset';
case USER_VERIFY_EMAIL = 'user verify email';
case USER_ADD_DEBIT_CARD = 'user add debit card';
case USER_TOP_UP_WALLET = 'user top up wallet';
case USER_MAKE_CARD_ACTIVE = 'user make card active';
case USER_DELETE_DEBIT_CARD = 'user delete debit card';
case USER_EDIT_FAVOURITE_MEAL = 'user edit favorite meal';
case USER_MAKE_RESERVATION = 'user make reservation';
case USER_PLACE_ORDER = 'user place order';

case ADMIN_SIGN_UP = 'admin signup';
case ADMIN_SIGN_IN = 'admin sign in';
case ADMIN_PASSWORD_CHANGE = 'admin password change';
case ADMIN_PASSWORD_RESET = 'admin password reset';
case ADMIN_UPDATE_RESTAURANT_STATUS = 'admin update restaurant status';
case ADMIN_DELETE_RESTAURANT = 'admin delete restaurant';
case ADMIN_UPDATE_CUSTOMER_STATUS = 'admin update customer status';
case ADMIN_DELETE_CUSTOMER = 'admin delete customer';

case VENDOR_SIGN_UP = 'vendor signup';
case VENDOR_SIGN_IN = 'vendor sign in';
case VENDOR_PASSWORD_CHANGE = 'vendor password change';
case VENDOR_PASSWORD_RESET = 'vendor password reset';
case VENDOR_ADD_ANOTHER_VENDOR = 'vendor add another vendor';
case VENDOR_DELETE_ANOTHER_VENDOR = 'vendor delete another vendor';
case VENDOR_UPDATE_PROFILE = 'vendor update profile';
case VENDOR_UPDATE_BANK_DETAILS = 'vendor update bank details';
case VENDOR_UPDATE_RESTAURANT_DETAILS = 'vendor update restaurant details';
case VENDOR_ADD_MENU = 'vendor add menu';
case VENDOR_UPDATE_MENU = 'vendor update menu';
case VENDOR_DELETE_MENU = 'vendor delete menu';
case VENDOR_MAKE_WITHDRAWAL_REQUEST = 'vendor make withdrawal request';
case VENDOR_UPDATE_ORDER_STATUS = 'vendor update order status';
    }
