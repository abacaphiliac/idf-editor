<?php

namespace IdfEditorTest\Action;

use IdfEditor\Action\EmnRequest;
use IdfEditor\File\EmnFile;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use Zend\Filter\File\RenameUpload;
use Zend\Http\PhpEnvironment\Request;
use Zend\Validator\File\Upload;

/**
 * @covers \IdfEditor\Action\EmnRequest
 * @covers \IdfEditor\File\EmnFile
 * @covers \IdfEditor\File\ResultFile
 */
class EmnRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var vfsStreamFile */
    private $upload;

    /** @var vfsStreamDirectory */
    private $uploads;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Upload */
    private $uploadValidator;

    /** @var \PHPUnit_Framework_MockObject_MockObject|RenameUpload */
    private $uploadMover;

    /** @var EmnRequest */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->uploads = vfsStream::setup('uploads');
        $this->upload = vfsStream::newFile('php0DMj7C');
        $this->uploads->addChild($this->upload);

        file_put_contents($this->upload->url(), $contents = <<<EMN

EMN
        );

        $this->uploadValidator = $this->getMockBuilder(Upload::class)->disableOriginalConstructor()->getMock();
        $this->uploadMover = $this->getMockBuilder(RenameUpload::class)->disableOriginalConstructor()->getMock();

        $this->sut = new EmnRequest(
            new \DirectoryIterator($this->uploads->url()),
            $this->uploadValidator,
            $this->uploadMover
        );
    }

    public function testGetFile()
    {
        $request = new Request();
        $request->getFiles()->set('emnFile', [
            'name' => 'generic.emn',
            'type' => 'application/octet-stream',
            'tmp_name' => $this->upload->url(),
            'error' => 0,
            'size' => strlen($this->upload->getContent()),
        ]);

        // Pretend the upload was successful. It is difficult to fake uploads in PHP.
        $this->uploadValidator->method('isValid')->willReturn(true);

        // Pretend the upload was moved. It is difficult to fake uploads in PHP.
        $this->uploadMover->method('filter')->willReturn($this->upload->url());

        $emnFile = $this->sut->getFile($request);

        self::assertInstanceOf(EmnFile::class, $emnFile);
    }
}

