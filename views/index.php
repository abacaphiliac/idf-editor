<?php
    
    include_once(dirname(__FILE__) . "/parser.php");
    
    if ($_FILES["emnFile"]["size"] > 0) {
		if (!move_uploaded_file($_FILES["emnFile"]["tmp_name"], dirname(__FILE__) . "/files/upload/" . $_FILES["emnFile"]["name"])) {
			exit("Unable to save temporary file.");
		}
        
        list ($boardInfo,$componentList) = parseEmnFile($_FILES["emnFile"]["name"]);
        $numberOfFields = count($componentList[key($componentList)]["csv"]);
        
        ob_start();
        ?>
        <form action="generateResultFile.php" id="componentForm" method="post" name="componentForm" >
            <input id="formType" name="formType" type="hidden" value="component" />
            <input id="fileName" name="fileName" type="hidden" value="<?=$_FILES["emnFile"]["name"];?>" />
			<div id="headerText" >
				<em><?=$_FILES["emnFile"]["name"];?></em><br/>
				<br/>
				<br/>
				<input id="toggleAll" name="toggleAll" type="checkbox" /> Select all/none<br/>
				<br/>
			</div>
            <table id="componentTable" class="tablesorter" >
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
					<?php
					$i = 0;
					foreach ($componentList as $componentName => $component) {
						?>
						<tr class="<?=($i++%2==0)?"even":"odd";?>" >
							<td><input id="<?=$componentName;?>" name="<?=$componentName;?>" type="checkbox" /></td>
							<?php
							foreach ($component["csv"] as $value) {
								?><td><?=$value;?></td><?php
							}
							?>
						</tr>
						<?php
					}
					?>
				</tbody>
            </table>
			<br/>
			<br/>
			<input id="componentSubmit" name="componentSubmit" type="submit" value="Generate file" />
			<span id="resultFileResponse" ></span><br/>
			<br/>
			<br/>
        </form>
        <?php
        $componentForm = ob_get_contents();
        ob_end_clean();
        
        $componentFormDisplay = "style='display:block;'";
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>IDF Converter</title>
        
        <link href="css/idf.css" rel="stylesheet" type="text/css" />
        <link href="css/tablesorter.css" rel="stylesheet" type="text/css" />
        
        <script src="js/lib/jquery-1.3.2.min.js" type="text/javascript" ></script>
        <script src="js/lib/jquery.tablesorter.min.js" type="text/javascript" ></script>
        <script src="js/lib/jquery.metadata.js" type="text/javascript" ></script>
		
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