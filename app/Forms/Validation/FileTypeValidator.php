<?php
/**
 * @package edu.uw.uaa.college
 */

/**
 * Validate that an uploaded file is an accepted type
 */

namespace App\Forms\Validation;

use App\Core\MimeType\MimeType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTypeValidator
{
    protected $allow;

    public function __construct(array $allow, $allow_list_mimetypes = false)
    {
        if ($allow_list_mimetypes) {
            $this->setAllowedMimeTypes($allow);
        } else {
            $this->setAllowedExtensions($allow);
        }
    }

    public function isAllowedType(UploadedFile $upload)
    {
        if (function_exists('finfo_open')) {
            $mimetype = $upload->getMimeType();
        } else {
            $mimetype = (new MimeType())->getMimeType($upload->getClientOriginalExtension());
        }
        return in_array($mimetype, $this->allow);
    }

    public function setAllowedExtensions(array $allow)
    {
        $this->allow = [];
        $extToMime = new MimeType();
        foreach ($allow as $ext) {
            $ext = ltrim($ext, '.');
            $this->allow[] = $extToMime->getMimeType($ext);
        }
    }

    public function setAllowedMimeTypes(array $allow)
    {
        $this->allow = $allow;
    }

}
