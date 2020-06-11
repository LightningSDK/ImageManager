<?php

namespace lightningsdk\imagemanager\Model;

class Image {
    public static function getImage($imageUrl, $size, $format) {
        // Image manager is enabled, so use the image manager version
        return '/image?' . http_build_query([
                'i' => $imageUrl,
                's' => $size,
                'f' => $format,
            ]);
    }
}