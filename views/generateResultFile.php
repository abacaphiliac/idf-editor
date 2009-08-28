<?php
    
    include_once(dirname(__FILE__) . "/parser.php");
    
    if ($_REQUEST["formType"] == "component" && $_REQUEST["fileName"] != "" ) {
        
		$t = time();
		
		// A little housekeeping ...
		if ($dh = opendir('/path/to/files')) {
			while (false !== ($file = readdir($dh))) {
				if (substr($file,0,1) != ".") {
					$stats = stat($file);
					if ($stats["mtime"] < ($t - 86400)) {
						unlink($file);
					}
				}
			}
		}
		
        $writeFileName = $_REQUEST["fileName"] . "." . $t . ".emn";
        
        list($boardInfo,$componentList) = parseEmnFile($_REQUEST["fileName"]);
        
        $file = fopen("files/download/" . $writeFileName, "w") or exit("Unable to open write file!");
        
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