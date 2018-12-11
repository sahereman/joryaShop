<?php

return [
    'parents' => [
        'Hair Cap' => '头套',
        'Dyed Slice' => '发块',
        'Accessories' => '配件',
        'Wiggery Articles' => '单品',
    ],
    'children' => [
        '头套' => [
            // '女士',
            // '男士',
            // '中老年',
            'Short' => '短款',
            'Medium' => '中款',
            'Long' => '长款',
            'Curly' => '卷发',
            'Straight' => '直发',
        ],
        '发块' => [
            // '女士发块',
            // '男士发块',
            // '中老年发块',
            'Wild Card' => '百搭发块',
            'Modern Style' => '摩登发块',
            'Vintage' => '经典发块',
        ],
        '配件' => [
            'Hair Bang' => '刘海',
            // '花苞',
            'Ponytail' => '马尾',
            'Half Wig' => '半假发',
            // '接发系列',
        ],
        '单品' => [
            'Wiggery Musts' => '假发必备',
            'Fashion Articles' => '时尚单品',
        ],
    ],
];
