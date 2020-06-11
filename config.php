<?php

return [
    'routes' => [
        'static' => [
            'image' => \lightningsdk\imagemanager\Pages\Image::class,
        ],
    ],
    'modules' => [
        'imageManager' => [
            'sourceImagePath' => HOME_PATH . '/images',
            'generatedImagePath' => HOME_PATH . '/images/generated',
            'jpgQuality' => 80,
            'sizes' => [
                250, 500, 750, 1000
            ],
            'backgroundColor' => [255, 255, 255],
        ],
    ],
];