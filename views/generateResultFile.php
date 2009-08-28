<?php
    
    include_once(dirname(__FILE__) . "/parser.php");
    
    if ($_REQUEST["formType"] == "component" && $_REQUEST["fileName"] != "" ) {
        
		$time = time();
		
		// A little housekeeping ...
		$directoriesToClean = array(dirname(__FILE__) . "/files/download", dirname(__FILE__) . "/files/upload");
		foreach ($directoriesToClean as $pathToFiles) {
			if ($dh = opendir($pathToFiles)) {
				while (false !== ($fileName = readdir($dh))) {
					if (substr($fileName,0,1) != ".") {
						$pathToFile = $pathToFiles . "/" . $fileName;
						$stats = stat($pathToFile);
						if ($stats["mtime"] < ($time - 86400)) {
							unlink($pathToFile);
						}
					}
				}
			}
		}
		
        $writeFileName = $_REQUEST["fileName"] . "." . $time . ".emn";
        
        list($boardInfo,$componentList) = parseEmnFile($_REQUEST["fileName"]);
        
        $file = fopen(dirname(__FILE__) . "/files/download/" . $writeFileName, "w") or exit("Unable to open write file!");
        
		foreach ($boardInfo as $line) {
            fwrite($file,$line);
		}
		
		fwrite($file,".PLACEMENT\n");
        foreach ($componentList as $componentName => $component) {
            if ($_REQUEST[$componentName] == "on") {
                fwrite($file,$component["line1"] . $component["line2"]);
            }
        }
		fwrite($file,".END_PLACEMENT\n");
        
        fclose($file);
    }
    
    exit($writeFileName);
?>