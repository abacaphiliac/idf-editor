<?php
   
   include_once(dirname(__FILE__) . "/parser.php");
   
   $readFileName = $_REQUEST["fileName"];
   $writeFileName = $readFileName . "." . time() . ".txt";
   
   if ($_REQUEST["formType"] == "component" && $readFileName != "" ) {
      
      $componentList = parseEmnFile($_REQUEST["fileName"]);
      
      $file = fopen($writeFileName, "w") or exit("Unable to open file!");
      
      foreach ($componentList as $componentName => $component) {
         if ($_REQUEST[$componentName] == "on") {
            fwrite($file,$component["line1"] . $component["line2"]);
         }
      }
      
      fclose($file);
   }
   
   exit($writeFileName);
?>