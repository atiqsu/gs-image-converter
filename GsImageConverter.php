<?php

/**
 * Class GsImageConverter
 *
 * Utility class to convert one image file type to another. Supported formats: JPEG, GIF, PNG
 *
 * FORMAT 1: Specify source image and extension to convert to.
 * GsImageConverter::convert('/home/gs/my_png.png', 'jpeg');
 *
 * FORMAT 2: Specify source image and destination path for new image with new extension.
 * GsImageConverter::convert('/home/gs/my_png.png', '/home/gs/new/my_new_file.jpeg);
 *
 * NO GUARANTEES. PLEASE TEST AND USE AT YOUR OWN RISK
 * Garagesocial, Inc. - www.garagesocial.com
 */
class GsImageConverter
{

    /**
     * Facade to user. Handle different input formats.
     *
     * @param string $imageSourcePath
     * @param string $target One of two formats. FORMAT 1: gif FORMAT 2: /var/tmp/mynewfile.gif
     */
    public static function convert($imageSourcePath, $target)
    {
        self::validateImage($imageSourcePath);
        list($imageDestinationPath, $imageDestinationType) = self::parseInputFormat($imageSourcePath, $target);
        $imageResourceSource = self::getImageAsResource($imageSourcePath);
        $imageResourceDestination = self::getImageTrueColorEquivalent($imageResourceSource);
        self::imageResampleFromReference($imageResourceDestination, $imageResourceSource);
        self::saveFileToDisk($imageDestinationType, $imageResourceDestination, $imageDestinationPath);
    }

    /**
     * Return a standard format of array from input
     *
     * @param string $imageSourcePath
     * @param string $target
     * @return array Array of length 2. (destination of image, type of destination image)
     */
    private static function parseInputFormat($imageSourcePath, $target)
    {
        $imageDestinationPath = null;
        $imageDestinationType = null;

        if (in_array($target, array('jpeg', 'gif', 'png'))) {
            // retrieve the image type directly from php constants
            $imageDestinationType = self::getImageTypeConstant($target);
            // create directory if needed
            $imageDestinationDirPath = dirname($imageSourcePath);
            // re-use the directory and file name path but use new extension
            $imageDestinationPath = $imageDestinationDirPath . '/' . self::getImagePathInfo(
                $imageSourcePath,
                'filename'
              ) . ".$target";
        } else {
            $imageDestinationPath = $target;
            $imageDestinationDirPath = dirname($imageDestinationPath);
            $imageDestinationType = self::getImageTypeConstant(self::getImagePathInfo($target, 'extension'));
        }

        // create directory if needed
        $imageDestinationDirPath = dirname($imageDestinationPath);
        is_dir($imageDestinationDirPath) or mkdir($imageDestinationDirPath, 0700, true);

        return array($imageDestinationPath, $imageDestinationType);
    }

    /**
     * Returns image resource
     *
     * @param string $imagePath
     * @return resource
     */
    private static function getImageAsResource($imagePath)
    {
        $imageType = self::getImageSize($imagePath, 'type');
        switch ($imageType) {
            case IMAGETYPE_GIF:
                return imagecreatefromgif($imagePath);
                break;
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                return imagecreatefrompng($imagePath);
                break;
            default :
                self::throwE("Unknown file type detected: $imagePath");
        }
    }

    /**
     * Returns a true color image using source as reference
     *
     * @param resource $imageSourceResource
     * @throw Exception When resource cannot be created
     * @return resource
     */
    private static function getImageTrueColorEquivalent($imageSourceResource)
    {
        $imageTrueColor = imagecreatetruecolor(
          imagesx($imageSourceResource),
          imagesy($imageSourceResource)
        ) or self::throwE(
          "Could not create true color image resource"
        );
        return $imageTrueColor;
    }

    /**
     * Get resampled image
     *
     * @param resource $imageDestinationResource
     * @param resource $imageSourceResource
     * @throw Exception When operation fails
     * @return resource
     */
    private static function imageResampleFromReference($imageDestinationResource, $imageSourceResource)
    {
        imagecopyresampled(
          $imageDestinationResource,
          $imageSourceResource,
          0,
          0,
          0,
          0,
          imagesx($imageSourceResource),
          imagesy($imageSourceResource),
          imagesx($imageSourceResource),
          imagesy($imageSourceResource)
        ) or self::throwE("Could not resample image");
    }

    /**
     * Get the image type PHP constant from extension
     *
     * @param string $extension
     * @return integer
     */
    private static function getImageTypeConstant($extension)
    {
        return constant("IMAGETYPE_" . strtoupper($extension));
    }

    /**
     * Given resource file and destination, save the file to disk
     *
     * @param integer  $imageType
     * @param resource $imageResource
     * @param string   $imageDestinationPath
     * @throws Exception On invalid file type
     */
    private static function saveFileToDisk($imageType, $imageResource, $imageDestinationPath)
    {
        switch ($imageType) {
            case IMAGETYPE_GIF  :
                imagegif($imageResource, $imageDestinationPath);
                break;
            case IMAGETYPE_JPEG :
                imagejpeg($imageResource, $imageDestinationPath, 100);
                break;
            case IMAGETYPE_PNG  :
                imagepng($imageResource, $imageDestinationPath, 9);
                break;
            default :
                self::throwE("Unknown file type: $imageType");
        }
    }

    /**
     * Helper to retrieve image pathinfo()
     *
     * @param string $imagePath
     * @param string $key Don't return full array. Valid options are: dirname, basename, extension, filename
     * @return string
     */
    private static function getImagePathInfo($imagePath, $key = null)
    {
        $pathInfo = pathinfo($imagePath);
        return !is_null($key) && isset($pathInfo[$key]) ? $pathInfo[$key] : $pathInfo;
    }

    /**
     * Helper to retrieve getimagesize()
     *
     * @param string $imagePath
     * @param string $key Don't return full array. Valid options are: width, height, type, bits, mime, channels
     * @throw Exception When info cannot get retrieved
     * @return mixed
     */
    private static function getImageSize($imagePath, $key = null)
    {
        $spec = getimagesize($imagePath) or self::throwE("Could not get image info: $imagePath");
        $spec['width'] = $spec[0];
        $spec['height'] = $spec[1];
        $spec['type'] = $spec[2];
        return !is_null($key) && isset($spec[$key]) ? $spec[$key] : $spec;
    }

    /**
     * Checks if image is valid
     *
     * @param string $imagePath
     */
    private static function validateImage($imagePath)
    {
        self::getImageSize($imagePath);
    }

    /**
     * Helper to throw exceptions inline with or
     *
     * @param string $message
     * @throws Exception
     */
    private static function throwE($message)
    {
        throw new Exception($message);
    }

}
