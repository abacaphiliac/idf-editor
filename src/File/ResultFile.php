<?php

namespace IdfEditor\File;

use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

class ResultFile
{
    /** @var EmnFile */
    private $emnFile;

    /** @var mixed[] */
    private $lines = [];

    /**
     * ResultFile constructor.
     * @param EmnFile $emnFile
     * @param Request $request
     */
    public function __construct(EmnFile $emnFile, Request $request)
    {
        $this->emnFile = $emnFile;
        $this->lines = $emnFile->getBoardInfo();
        $componentList = $emnFile->getComponents();

        $this->lines[] = ".PLACEMENT";
        foreach ($componentList as $i => $component) {
            if ($request->getPost('component_' . $i)) {
                $this->lines[] = $component["line1"] . "\n" . $component["line2"];
            }
        }
        $this->lines[] = ".END_PLACEMENT";
    }

    /**
     * @return \mixed[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        $title = $this->emnFile->getTitle();

        $filename = implode('.', [
            pathinfo($title, PATHINFO_FILENAME),
            gmdate('c'),
            pathinfo($title, PATHINFO_EXTENSION),
        ]);

        $content = implode("\n", $this->lines) . "\n";

        $response = new Response();
        $response->setContent($content);
        $response->getHeaders()->addHeaders([
            'Content-Type' => 'text-plain',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Content-Length' => strlen($content),
        ]);

        return $response;
    }
}