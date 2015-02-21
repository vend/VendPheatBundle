<?php

$container->loadFromExtension('pheat', [
    'features' => [
        'on_only_in_dev'     => false,
        'on_only_in_main'    => true,
        'on_only_in_prod'    => false,
        'on_only_in_test'    => false,
        'ratio_feature'      => 0.1000000000000000055511151231257827021181583404541015625,
        'ratio_feature2'     => '50%',
        'ratio_feature_full' => [
            'enabled' => true,
            'vary'    => 'username',
            'ratio'   => 0.1000000000000000055511151231257827021181583404541015625,
        ],
        'variant_feature'    => [
            'enabled'  => true,
            'vary'     => 'username',
            'variants' => [
                'variant_one'   => 30,
                'variant_two'   => 30,
                'variant_three' => 'default',
                'variant_four'  => 10,
            ],
        ],
    ],
    'manager'   => [
        'class' => 'Pheat\\Manager',
    ],
    'providers' => [
        'session' => true,
        'config'  => true,
    ],
]);
