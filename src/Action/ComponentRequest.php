<?php

namespace IdfEditor\Action;

use IdfEditor\File\EmnFile;
use IdfEditor\File\ResultFile;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

class ComponentRequest
{
    /**
     * @param Request $request
     * @return Response
     */
    public function getResponse(Request $request)
    {
        $emnFile = new EmnFile($request->getPost('fileName'), $request->getPost('fileTitle'));
        $resultFile = new ResultFile($emnFile, $request);

        return $resultFile->getResponse();
    }
}
