<?php

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field\MultipleImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtraMultiImage extends MultipleImage
{
    protected $isDeletable = false;

    /**
     * Destroy original files.
     *
     * @return string.
     */
    public function destroy($key)
    {
        $files = $this->original ?: [];

        $file = array_get($files, $key);

        if (!$this->isDeletable) {
            if ($this->storage->exists($file)) {
                $this->storage->delete($file);
            }
        }

        unset($files[$key]);

        return array_values($files);
    }

    public function deletable($bool = false)
    {
        $this->isDeletable = $bool;

        return $this;
    }
}
