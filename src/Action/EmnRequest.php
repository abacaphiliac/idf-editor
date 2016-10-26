<?php

namespace IdfEditor\Action;

use IdfEditor\File\EmnFile;
use Zend\Filter\File\RenameUpload;
use Zend\Http\PhpEnvironment\Request;
use Zend\Validator\File\Upload;

class EmnRequest
{
    /** @var \DirectoryIterator */
    private $uploads;

    /** @var Upload */
    private $uploadValidator;

    /** @var RenameUpload */
    private $uploadMover;

    /**
     * EmnRequest constructor.
     * @param \DirectoryIterator $uploads
     * @param Upload $uploadValidator
     * @param RenameUpload $uploadMover
     */
    public function __construct(
        \DirectoryIterator $uploads,
        Upload $uploadValidator = null,
        RenameUpload $uploadMover = null
    ) {
        $this->uploads = $uploads;
        $this->uploadValidator = $uploadValidator ?: new Upload();
        $this->uploadMover = $uploadMover ?: new RenameUpload([
            'target' => $uploads->getPath(),
            'use_upload_name' => true,
            'use_upload_extension' => true,
            'overwrite' => true,
            'randomize' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return EmnFile
     */
    public function getFile(Request $request)
    {
        $uploadedEmnFile = $request->getFiles('emnFile', []);
        $emnTempName = \igorw\get_in($uploadedEmnFile, ['tmp_name'], null);

        // TODO Validate .emn file extension?
        $emnFileTitle = \igorw\get_in($uploadedEmnFile, ['name'], null);

        // Validate uploaded file.
        if (!$this->uploadValidator->isValid('emnFile', $uploadedEmnFile)) {
            throw new \InvalidArgumentException(
                'File upload failed: ' . json_encode($this->uploadValidator->getMessages())
            );
        }

        foreach ($this->uploads as $item) {
            if ($item->getBasename() === '.gitignore') {
                continue;
            }

            if ($item->isFile() && time() - $item->getMTime() >= 60 * 60 * 24) {
                unlink($item->getRealPath());
            }
        }

        $emnFileName = $this->uploadMover->filter($emnTempName);

        // Parse the uploaded file.
        return new EmnFile($emnFileName, $emnFileTitle);
    }
}
