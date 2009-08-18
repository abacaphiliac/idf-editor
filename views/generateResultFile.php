<?php
   
   include_once(dirname(__FILE__) . "/parser.php");
   
   if ($_REQUEST["formType"] == "component" && $_REQUEST["fileName"] != "" ) {
      
      $writeFileName = $_REQUEST["fileName"] . "." . time() . ".emn";
      
      $componentList = parseEmnFile($_REQUEST["fileName"]);
      
      $file = fopen("files/download/" . $writeFileName, "w") or exit("Unable to open write file!");
      
      foreach ($componentList as $componentName => $component) {
         if ($_REQUEST[$componentName] == "on") {
            fwrite($file,$component["line1"] . $component["line2"]);
         }
      }
      
      fclose($file);
   }
   
   exit($writeFileName);
?>