<?php
   
   function parseEmnFile($fileName) {
      $file = fopen("files/upload" . $fileName, "r") or exit("Unable to open read file!");
      
      while(!feof($file)) {
         $line = fgets($file);
         if (strlen(trim($line)) > 0 && trim($line) == ".PLACEMENT") {
            while (!feof($file)) {
               $line = fgets($file);
               if (strlen(trim($line)) > 0 && trim($line) != ".END_PLACEMENT") {
                  $lines[] = $line;
               }
            }
         }
      }
      fclose($file);
     
      for ($i=0; $i<count($lines); $i+=2) {
         $component["line1"] = $lines[$i];
         $component["line2"] = $lines[$i+1];
         $component["csv"] = explode(",",preg_replace("/\s+/",",",trim($component["line1"]) . " " . trim($component["line2"])));
         $componentList[] = $component;
      }
      /* ?><pre><?php exit(print_r($componentList)); ?></pre><?php //*/
      
      foreach ($componentList as $component) {
         $componentListByHashValue[md5(serialize($component))] = $component;
      }
      /* ?><pre><?php exit(print_r($componentListByHashValue)); ?></pre><?php //*/
      
      return $componentListByHashValue;
   }
   
?>