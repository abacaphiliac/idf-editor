<?php

namespace IdfEditorTest\Action;

use IdfEditor\Action\ComponentRequest;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * @covers \IdfEditor\Action\ComponentRequest
 * @covers \IdfEditor\File\EmnFile
 * @covers \IdfEditor\File\ResultFile
 */
class ComponentRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var ComponentRequest */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new ComponentRequest();
    }

    public function testGetResponse()
    {
        $request = new Request();
        $request->getPost()->set('fileName', __DIR__ . '/generic.emn');
        $request->getPost()->set('fileTitle', 'generic.emn');
        $request->getPost()->set('component_0', 'on');
        $request->getPost()->set('component_2', 'on');

        $response = $this->sut->getResponse($request);

        self::assertInstanceOf(Response::class, $response);
        self::assertEquals(file_get_contents(__DIR__ . '/expected.emn'), $response->getContent());
    }
}
