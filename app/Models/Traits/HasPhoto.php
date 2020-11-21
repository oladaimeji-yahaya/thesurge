<?php

namespace App\Models\Traits;

use App\Models\Organization;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Category;
use App\Models\Entry;
use Illuminate\Support\Facades\Storage;
use App\Models\Channel;

/**
 * Class GetPhotoUrl
 *
 * @package App\Models\Traits
 */
trait HasPhoto {

    /**
     * @param $a
     *
     * @return string
     */
    protected function getPhotoUrlAttribute($a)
    {
        return $this->getPhotoUrl();
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        if ($this->hasPhotoOnLD()) {
            if (!is_file(storage_path('app' . DS . self::IMAGE_DIR . DS . $this->photo))) {
                return $this->defaultPhotoUrl();
            }

            return normalizeUrl(asset(str_replace('public', 'storage', self::IMAGE_DIR) . '/' . $this->photo));
        }

        return $this->photo;
    }

    /**
     * @return bool
     */
    public function hasPhotoOnLd()
    {
        return !starts_with($this->photo, 'http');
    }

    public static function defaultPhotoUrl()
    {
        $img = 'user.png';
        switch (self::class) {
            case User::class :
                $img = 'user.png';
                break;
            case Organization::class :
                $img = 'organization.png';
                break;
            case Publisher::class :
                $img = 'publisher.png';
                break;
            case Category::class :
                $img = 'tag.png';
                break;
            case Entry::class :
                $img = 'entry.png';
                break;
            case Channel::class :
                $img = 'channel.png';
                break;
        }

        return (env('APP_URL') . '/images/defaults/' . $img);
    }

    public function deletePhoto()
    {
        $photo = storage_path('app' . DS . self::IMAGE_DIR . DS . $this->photo);
        if ($this->hasPhotoOnLD() and is_file($photo)) {
            unlink($photo);
        }
    }

    protected function getThumbAttribute($a)
    {
        return $this->getThumbnail();
    }

    public function deleteThumbnail()
    {
        $thumb = storage_path('app' . DS . self::IMAGE_DIR . DS . 'thumbs' . DS . $this->photo);
        if ($this->hasPhotoOnLD() and is_file($thumb)) {
            unlink($thumb);
        }
    }

    /**
     * Get or Create thumbnail
     * @param boolean $createNew Force to create new thumbnail
     * @param string $old Old URL
     * @return string URL of thumbnail. Thumbnail is created if it does not exist
     * else old image URL is returned if conversion failed
     */
    public function getThumbnail($createNew = false, $old = '')
    {
        if ($this->hasPhotoOnLD()) {
            $destination = storage_path('app' . DS . self::IMAGE_DIR . DS . 'thumbs' . DS . $this->photo);
            $destLink = normalizeUrl(asset(str_replace('public', 'storage', self::IMAGE_DIR) . '/thumbs/' . $this->photo));
            //Return thumbnail if it exists
            if (is_file($destination) && !$createNew) {
                return $destLink;
            }

            //Create new
            $file = storage_path('app' . DS . self::IMAGE_DIR . DS . $this->photo);
            if (is_file($file)) {
                $max_size = $this instanceof Entry ? 100 : 50;
                $jpeg_quality = 90;
                $old = empty($old) ? $old : storage_path('app' . DS . self::IMAGE_DIR . DS . 'thumbs' . DS . $old);
                if (is_file($old)) {
                    unlink($old);
                }

                $url = $this->resize_image($file, $destination, $max_size, $jpeg_quality);
                return $url ? $destLink : $this->getPhotoUrl();
            }
        }
        return $this->getPhotoUrl();
    }

    /**
     * Proportionally resize image
     * @param string $source Source image URL
     * @param string $destination Destination image URL
     * @param float $max_size Maximum size
     * @param int $quality Jpeg image quality
     * @return boolean
     */
    private function resize_image($source, $destination, $max_size, $quality)
    {

        $image_size_info = getimagesize($source); //get image size
        if ($image_size_info) {
            $image_width = $image_size_info[0]; //image width
            $image_height = $image_size_info[1]; //image height
        } else {
            return false;
        }

        //return false if nothing to resize
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        }
        //do not resize if image is smaller than max size
        if ($image_width <= $max_size && $image_height <= $max_size) {
            return false;
        }

        //Construct a proportional size of new image
        $image_scale = min($max_size / $image_width, $max_size / $image_height);
        $new_width = ceil($image_scale * $image_width);
        $new_height = ceil($image_scale * $image_height);

        //Create a new true color image
        $new_image = imagecreatetruecolor($new_width, $new_height);
        if ($this instanceof Entry) {
            //White background, no transparency
            $trans_colour = imagecolorallocate($new_image, 255, 255, 255);
            imagefill($new_image, 0, 0, $trans_colour);
        } else {
            //Retain transparency
            imagesavealpha($new_image, true);
            $trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $trans_colour);
        }

        //Copy and resize part of an image with resampling
        $sourceResource = $this->getImageResource($source);
        if (imagecopyresampled($new_image, $sourceResource, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            return $this->saveImage($new_image, $destination, $image_size_info['mime'], $quality);
        } else {
            return false;
        }
    }

    public function cropImage($x, $y, $width, $height)
    {
        //return false if nothing to resize
        if ($width <= 0 || $height <= 0 || is_nan($x) || is_nan($y)) {
            return false;
        }
        $file = storage_path('app' . DS . self::IMAGE_DIR . DS . $this->photo);
        //Create a new true color image
        $new_image = imagecreatetruecolor($width, $height);
        //Retain transparency
        imagesavealpha($new_image, true);
        $trans_colour = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagefill($new_image, 0, 0, $trans_colour);
        //Copy and resize part of an image with resampling
        $source = $this->getImageResource($file);
        if ($source) {
            if (imagecopyresampled($new_image, $source, 0, 0, $x, $y, $width, $height, $width, $height)) {
                $dest = storage_path('app' . DS . 'RAM' . DS . self::IMAGE_DIR . DS . $this->photo);
                if ($this->saveImage($new_image, $dest, getimagesize($file)['mime'], 50)) {
                    Storage::delete(self::IMAGE_DIR . "/{$this->photo}");
                    return Storage::move('RAM/' . self::IMAGE_DIR . "/{$this->photo}", self::IMAGE_DIR . "/{$this->photo}");
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @param resource $source Image resource
     * @param string $destination Destination file
     * @param string $mime Source mime type
     * @param int $quality Jpeg quality (for jpeg images)
     * @return boolean
     */
    private function saveImage($source, $destination, $mime, $quality)
    {
        //Create directory
        $split = explode(DS, str_replace('/', DS, $destination));
        $filename = array_pop($split); //Remove filename
        $dir = implode(DS, $split);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        //save resized image
        switch (strtolower($mime)) {
            case 'image/png':
                $ok = imagepng($source, $destination);
                break; //save png file
            case 'image/gif':
                $ok = imagegif($source, $destination);
                break; //save gif file
            case 'image/jpeg': case 'image/pjpeg': //Save jpg/jpeg file
                $ok = imagejpeg($source, $destination, $quality);
                break; //save jpeg file
            default: return false;
        }
        if ($ok) {
            return $destination;
        }
        unlink($destination);
        return false;
    }

    private function getImageResource($source)
    {
        $sourceResource = false;
        $imageInfo = is_file($source) ? getimagesize($source) : false;
        if ($imageInfo) {
            switch (strtolower($imageInfo['mime'])) {
                case 'image/png':
                    $sourceResource = imagecreatefrompng($source);
                    break;
                case 'image/gif':
                    $sourceResource = imagecreatefromgif($source);
                    break;
                case 'image/jpeg': case 'image/pjpeg':
                    $sourceResource = imagecreatefromjpeg($source);
                    break;
            }
        }
        return $sourceResource;
    }

}
