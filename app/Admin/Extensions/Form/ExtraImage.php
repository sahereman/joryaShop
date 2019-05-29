<?php

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtraImage extends Image
{
    protected $isDeletable = false;

//    /**
//     * Upload file and delete original file.
//     * @param UploadedFile $file
//     * @return mixed
//     */
//    protected function uploadAndDeleteOriginal(UploadedFile $file)
//    {
//        $this->renameIfExists($file);
//
//        $path = $this->storage->putFileAs($this->getDirectory(), $file, $this->name);
//
//        if (!$this->isDeletable)
//        {
//            $this->destroy();
//        }
//
//        return $path;
//    }

    public function deletable($bool = false)
    {
        $this->isDeletable = $bool;

        return $this;
    }


    public function destroy()
    {
        if (!$this->isDeletable)
        {
            if ($this->storage->exists($this->original))
            {
                $this->storage->delete($this->original);
            }
        }
    }
}
