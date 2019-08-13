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

    /**
     * Generate a unique name for uploaded file.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function generateUniqueName(UploadedFile $file)
    {
        $i = 0;
        $file_name = $file->getClientOriginalName();
        $name = pathinfo($file_name, PATHINFO_FILENAME);
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        while ($this->storage->exists("{$this->getDirectory()}/{$file_name}")) {
            $file_name = $name . '-' . $i . '.' . $extension;
            $i++;
        }
        return $file_name;
    }
}
