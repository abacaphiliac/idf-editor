<?php
   
   include_once(dirname(__FILE__) . "/parser.php");
   
   function getLine($fh) {
      return preg_replace("/\s+/",",",trim(fgets($fh)));
   }
   
   if ($_FILES["emnFile"]["size"] > 0) {
		if (!move_uploaded_file($_FILES["emnFile"]["tmp_name"],$_FILES["emnFile"]["name"])) {
			exit("Unable to save temporary file.");
		}
      
      $componentList = parseEmnFile($_FILES["emnFile"]["name"]);
      
      ob_start();
      ?>
      <em><?=$_FILES["emnFile"]["name"];?></em><br/>
      <br/>
      <form action="index.php" id="componentForm" method="post" name="componentForm" >
         <input id="formType" name="formType" type="hidden" value="component" />
         <input id="fileName" name="fileName" type="hidden" value="<?=$_FILES["emnFile"]["name"];?>" />
         <table id="componentTable" name="componentTable" >
            <tr>
               <td><input type="checkbox" /></td>
               <td colspan="<?=count($componentList[0]["csv"]);?>" >
                  <br/>
                  <br/>
               </td>
            </tr>
            <?php
			$i = 1;
            foreach ($componentList as $componentName => $component) {
               ?>
               <tr>
                  <td><input id="cb<?=$i++;?>" name="cb<?=$i++;?>" type="checkbox" value="<?=$componentName;?>" /></td>
                  <?php
                  foreach ($component["csv"] as $value) {
                     ?><td><?=$value;?></td><?php
                  }
                  ?>
               </tr>
               <?php
            }
            ?>
            <tr>
               <td>
                  <br/>
               </td>
               <td colspan="<?=count($componentList[0])+count($componentList[1]);?>" >
                  <br/>
                  <input id="componentSubmit" name="componentSubmit" type="submit" value="Generate file" />
               </td>
            </tr>
         </table>
      </form>
      <?php
      $componentForm = ob_get_contents();
      ob_end_clean();
      
      $componentFormDisplay = "style='display:block;'";
   }
   
   if ($_REQUEST["formType"] == "component") {
      
   }
   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
   <head>
      <title>IDF Converter</title>
      
      <link href="css/idf.css" rel="stylesheet" type="text/css" />
      
      <script src="js/lib/jquery-1.3.2.min.js" type="text/javascript" ></script>
      <script src="js/idf.js" type="text/javascript" ></script>
   </head>
   <body>
      <h1>Input file</h1>
      <div class="form" >
         Select an EMN input file:<br/>
         <form action="index.php" enctype="multipart/form-data" id="emnForm" method="post" name="emnForm" >
            <input id="formType" name="formType" type="hidden" value="emn" />
            <input id="emnFile" name="emnFile" size="30" type="file" /> <input id="emnSubmit" name="emnSubmit" type="submit" value="Submit" /><br/>
         </form>
      </div>
      <div class="select" <?=$componentFormDisplay;?> >
         <h1>Select components from the list below</h1>
         <div class="form" >
            <?=$componentForm;?>
         </div>
      </div>
   </body>
</html>