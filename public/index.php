<?php

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require_once __DIR__ . "/../vendor/autoload.php";

$emnFile = null;

$request = new \Zend\Http\PhpEnvironment\Request();
if ($request->isPost()) {
    $response = null;

    switch ($formType = $request->getPost('formType')) {
        case 'component':
            // Components have been selected. Generate response object.
            $response = (new \IdfEditor\Action\ComponentRequest())->getResponse($request);
            break;
        case 'emn':
            $uploads = new \DirectoryIterator(__DIR__ . '/../data/uploads');
            $emnFile = (new \IdfEditor\Action\EmnRequest($uploads))->getFile($request);
            break;
        default:
            throw new \UnexpectedValueException(sprintf('%s is an unknown form type.', $formType));
            break;
    }

    if ($response instanceof \Zend\Http\PhpEnvironment\Response) {
        http_response_code($response->getStatusCode());
		foreach ($response->getHeaders() as $header) {
			header($header->toString());
		}
		echo $response->getContent();
		die;
	}
}
?>
<html>
    <head>
		<title>IDF Converter</title>
		<link href="jquery/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
		<link href="jquery/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
		<link href="jquery/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
		<link href="css/tablesorter.css" rel="stylesheet" type="text/css" />
		<link href="css/idf.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <p>
			<a href="https://github.com/abacaphiliac/idf-editor">Source &amp; Documentation</a>
			<a href="https://github.com/abacaphiliac/idf-editor/issues">Issue Tracker</a>
		</p>
        <h1>Upload File</h1>
        <div class="form" >
            <p>
                Select an EMN file.
            </p>
            <form action="" enctype="multipart/form-data" id="emnForm" method="post" name="emnForm" >
                <input id="formType" name="formType" type="hidden" value="emn" />
                <input id="emnFile" name="emnFile" size="30" type="file" accept=".emn" />
				<input id="emnSubmit" name="emnSubmit" type="submit" value="Submit" /><br/>
            </form>
        </div>
		<?php if ($emnFile instanceof \IdfEditor\File\EmnFile): ?>
        <div class="select" style="display:block;">
            <h1>Select Components</h1>
            <div class="form" >
				<form action="" id="componentForm" method="post" name="componentForm" >
					<input id="formType" name="formType" type="hidden" value="component" />
					<input id="fileName" name="fileName" type="hidden" value="<?=$emnFile->getUrl();?>" />
					<input id="fileTitle" name="fileTitle" type="hidden" value="<?=$emnFile->getTitle();?>" />
					<div id="headerText" >
                        <p>
						    <em><?=$emnFile->getTitle();?></em>
                        </p>
                        <p>
                            <label>
                                <input id="toggleAll" name="toggleAll" type="checkbox" /> Select all/none
                            </label>
                        </p>
					</div>
					<table id="componentTable" class="tablesorter">
						<thead>
						<tr>
							<th></th>
							<th>Part Number</th>
							<th>column 2</th>
							<th>Reference Designator</th>
							<th class="{sorter: 'digit'}" >column 4</th>
							<th class="{sorter: 'digit'}" >column 5</th>
							<th class="{sorter: 'digit'}" >column 6</th>
							<th class="{sorter: 'digit'}" >column 7</th>
							<th>column 8</th>
							<th>column 9</th>
						</tr>
						</thead>
						<tbody>
							<?php $i = 0; ?>
                            <?php foreach ($emnFile->getComponents() as $i => $component): ?>
							<?php $componentName = 'component_' . $i; ?>
							<tr class="<?=($i++%2==0)?"even":"odd";?>">
								<td>
									<input id="<?=$componentName;?>" name="<?=$componentName;?>" title="<?=$componentName;?>" type="checkbox" />
								</td>
								<?php foreach ($component["csv"] as $value): ?>
								<td><?=$value;?></td>
								<?php endforeach; ?>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<input id="componentSubmit" name="componentSubmit" type="submit" value="Download Result" />
				</form>
            </div>
        </div>
		<?php endif; ?>
		<script src="jquery/jquery-3.1.1.min.js" type="text/javascript" ></script>
		<script src="jquery/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript" ></script>
		<script src="jquery/jquery.metadata.js" type="text/javascript" ></script>
		<script src="jquery/jquery.tablesorter.min.js" type="text/javascript" ></script>
		<script src="js/idf.js" type="text/javascript" ></script>
    </body>
</html>
