<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait ImageTrait
{
    /**
     * upload Image
     */
    public function uploadImage($image, $folderName = 'uploads', $imageName = null)
    {
        $folderPath = public_path($folderName . '/' . $this->getImageFolderName());
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        if (!$imageName) {
            $imageName = $this->getImageFileName($image);
        }

        $imagePath = $folderName . '/' . $this->getImageFolderName() . '/' . $imageName;
        $image->move($folderPath, $imageName);
        return $imagePath;
    }

     public function updateImage($image, $folderName = 'uploads', $imageName = null)
    {
        $this->deleteImage($folderName);
        return $this->uploadImage($image, $folderName, $imageName);
    }

    /**
     *delete Image
     */
    public function deleteImage($folderName = 'uploads')
    {
        if ($this->image_path) {
            if (file_exists(public_path($this->image_path))) {
                unlink(public_path($this->image_path));
            }

            $folderPath = public_path($folderName . '/' . $this->getImageFolderName());
            if (File::exists($folderPath) && $this->isFolderEmpty($folderPath)) {
                File::deleteDirectory($folderPath);
            }
            
            $this->update(['image_path' => null]);
        }
    }

    /**
     *delete Image Folder
     */
    public function deleteImageFolder($folderName = 'uploads')
    {
        $folderPath = public_path($folderName . '/' . $this->getImageFolderName());
        if (File::exists($folderPath)) {
            File::deleteDirectory($folderPath);
            $this->update(['image_path' => null]);
        }
    }
    /**
     *  get Image Url
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path && file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }
        return $this->getDefaultImageUrl();
    }

    /**
     *  get Has Image
     */
    public function getHasImageAttribute()
    {
        return !empty($this->image_path) && file_exists(public_path($this->image_path));
    }

    /**
     * get Image FolderName
     */
    protected function getImageFolderName()
    {
        return $this->slug ?? $this->id;
    }

    /**
     * get Image FileName
     */
    protected function getImageFileName($image)
    {
        $extension = $image->getClientOriginalExtension();
        return 'image.' . $extension;
    }

    /**
     * get Default Image Url
     */
    protected function getDefaultImageUrl()
    {
        return asset('images/default-image.png');
    }

    /**
     * is Folder Empty
     */
    protected function isFolderEmpty($folderPath)
    {
        return count(File::allFiles($folderPath)) === 0;
    }

    /**
     * copy Image
     */
    public function copyImageFrom($model, $folderName = 'uploads')
    {
        if ($model->has_image) {
            $sourcePath = public_path($model->image_path);
            $destinationPath = public_path($folderName . '/' . $this->getImageFolderName() . '/' . basename($model->image_path));
            
            $destinationDir = dirname($destinationPath);
            if (!File::exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }

            File::copy($sourcePath, $destinationPath);
            return $folderName . '/' . $this->getImageFolderName() . '/' . basename($model->image_path);
        }

        return null;
    }
}