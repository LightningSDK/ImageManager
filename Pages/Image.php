<?php

namespace Modules\ImageManager\Pages;

use Exception;
use Lightning\Tools\Configuration;
use Lightning\Tools\Request;
use Lightning\View\API;
use Lightning\Tools\Image as LightningImage;

class Image extends API {

    /**
     * @throws Exception
     */
    public function get() {
        $image = Request::get('i');
        $size = Request::get('s', Request::TYPE_INT);
        $format = strtoupper(Request::get('f'));

        if ($format != LightningImage::FORMAT_JPG && $format != LightningImage::FORMAT_PNG) {
            throw new Exception('Invalid format');
        }

        if (!in_array($size, Configuration::get('modules.imageManager.sizes'))) {
            throw new Exception('Invalid size');
        }

        $fileName = Configuration::get('modules.imageManager.sourceImagePath') . '/' . preg_replace('/\.[a-z0-9]+$/', '', $image) . '_' . $size . '.' . strtolower($format);
        if (!file_exists($fileName)) {
            // Get the file path relative to the current directory
            if (preg_match('|^/|', $image)) {
                // The path is absolute realtive to the web directory
                $sourceFile = realpath(HOME_PATH . $image);
                if (
                    strpos($sourceFile, realpath(HOME_PATH)) !== 0
                    && strpos($sourceFile, realpath(Configuration::get('modules.imageManager.sourceImagePath'))) !== 0
                ) {
                    throw new Exception('Invalid files');
                }
            } else {
                // The path is relative to the stored image directory
                $sourceFile = realpath(Configuration::get('modules.imageManager.generatedImagePath') . '/' . $image);
            }

            if (empty($sourceFile)) {
                throw new Exception('Invalid file');
            }

            // If the file doesn't exist, create it.
            if (file_exists($sourceFile)) {
                // Make sure the directory exists
                if (!file_exists(dirname($fileName))) {
                    mkdir(dirname($fileName), 0775, true);
                }

                // Save the resized image
                $image = LightningImage::createFromString(file_get_contents($sourceFile));
                $image->process([
                    'max_size' => $size,
                    'background' => Configuration::get('modules.imageManager.backgroundColor'),
                ]);
                $image->save($fileName, [
                    'format' => $format,
                    'quality' => Configuration::get('modules.imageManager.jpgQuality'),
                ]);
            }
        }

        LightningImage::setHeader($format);
        readfile($fileName);
        exit;
    }

}
