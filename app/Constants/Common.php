<?php 

namespace App\Constants;

class Common
{
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2';
    const PRODUCT_PURCHASE = '3';
    const PRODUCT_CANCEL = '4';

    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,
        'reduce' => self::PRODUCT_REDUCE,
        'purchase' => self::PRODUCT_PURCHASE,
        'cancel' => self::PRODUCT_CANCEL,
    ];

    const ORDER_RECOMMEND = '0';
    const ORDER_HIGHER = '1';
    const ORDER_LOWER = '2';
    const ORDER_LATER = '3';
    const ORDER_OLDER = '4';
    const SORT_ORDER = [
    'recommend' => self::ORDER_RECOMMEND,
    'higherPrice' => self::ORDER_HIGHER,
    'lowerPrice' => self::ORDER_LOWER,
    'later' => self::ORDER_LATER,
    'older' => self::ORDER_OLDER
    ];
}


?>