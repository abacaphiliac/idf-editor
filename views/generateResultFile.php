<?php
   
   include_once(dirname(__FILE__) . "/parser.php");
   
   if ($_REQUEST["formType"] == "component" && $_REQUEST["fileName"] != "" ) {
      exit("generator!");
   }
   
?>