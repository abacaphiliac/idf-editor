<?php
   
   function parseEmnFile($fileName) {
      $file = fopen($fileName, "r") or exit("Unable to open file!");
      
      while(!feof($file)) {
         $line = getLine($file);
         if (strlen($line) > 0 && $line == ".PLACEMENT") {
            while (!feof($file)) {
               $line = trim(fgets($file));
               if (strlen($line) > 0 && $line != ".END_PLACEMENT") {
                  $lines[] = $line;
               }
            }
         }
      }
      fclose($file);
     
      for ($i=0; $i<count($lines); $i+=2) {
         $component["line1"] = $lines[$i];
         $component["line2"] = $lines[$i+1];
         $component["csv"] = explode(",",preg_replace("/\s+/",",",$component["line1"] . " " . $component["line2"]));
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