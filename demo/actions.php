<?php
// ----------------------------------------------------------------------------------------------------------------------------
// PHP for testing Dataprius API v2.0
// ----------------------------------------------------------------------------------------------------------------------------
require_once("config.php");
require_once("../dataprius-api.php");

$objApi = new DatapriusApi(API_CLIENT_ID,API_CLIENT_SECRET);

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" /> 
  <title>API Demo</title>
  <link rel="stylesheet" href="css/demo.css">
</head>

<body bgcolor="#ececec">
<h1 class="title" style="text-align:center;">Demo Test API Dataprius Cloud v.2.0</h1>

<div class="centercontent">
	<div class="whitebox" >
	<?php
		$action=isset($_REQUEST["Action"]) ? $_REQUEST["Action"] : "";
		
		switch($action)
		{
			case "CREATE-FOLDER":	
				
				$codeFolder=$_REQUEST["Folder"];				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Create Folder</h1><br>
						
					<form  method="post"  action="make.php?Action=CREATE-FOLDER&Folder=<?php echo $codeFolder?>">				  
					  <div class="nomtext">Name</div>
					  <input type="text" name="FolderName" >
					  <br><br>
					  <a class="button" href="index.php?Folder=<?php echo $codeFolder?>">Go Back</a>	
					  <button type="submit" class="button" value="Enter" >Create</button>				
					</form>
					<br>
							
				</div>
				<?php
			break;
			
			case "RENAME-FOLDER":
			
				$codeFolder=$_REQUEST["Folder"];				
				$data=$objApi->FolderInfo($codeFolder);
				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Rename Folder</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>

					<form  method="post"  action="make.php?Action=RENAME-FOLDER&Folder=<?php echo $codeFolder?>">
					  
					  <div class="nomtext">New name</div>
					  <input type="hidden" name="OldName" value="<?php echo $data->Name;?>">
					  <input type="text" name="NewName" >
					  <br><br>
					  <a class="button" href="index.php?Folder=<?php echo $codeFolder?>">Go Back</a>	
					  <button type="submit" class="button" value="Enter" >Rename</button>				
					</form>
					<br>

				</div>
				<?php
			break;
			
			case "DELETE-FOLDER":
			
				$codeFolder=$_REQUEST["Folder"];
				$codeParent=$_REQUEST["Parent"];
				$data=$objApi->FolderInfo($codeFolder);
								
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Delete Folder</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>
					<a href="make.php?Action=DELETE-FOLDER&Folder=<?php echo $codeFolder?>&Parent=<?php echo $codeParent?>" class="button">Delete</a>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
				</div>
				<?php
			break;		
			
			case "DELETE-FILE":
			
				$codeFile=$_REQUEST["File"];
				$codeFolder=$_REQUEST["Folder"];
				
				$data=$objApi->FileInfo($codeFile);			
				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Delete File</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>
					<a href="make.php?Action=DELETE-FILE&File=<?php echo $data->ID?>&Folder=<?php echo $codeFolder?>" class="button">Delete</a>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
				</div>
				<?php
			break;		
			
			case "UPLOAD-FILE":
			
				$codeFolder=$_REQUEST["Folder"];
				?>			
				<div class=" centercontent graybox">								
					<h1 class="title">Upload File</h1><br>
					<form  method="post" enctype="multipart/form-data" action="make.php?Action=UPLOAD-FILE&Folder=<?php echo $codeFolder?>">
					  Select file to upload:<br>
					  <input type="file" name="fileToUpload" id="fileToUpload">
					  <br><br>
					  <input type="submit" value="Upload File" name="submit">
					</form>
					
				</div>			
				<?php
			
			break;

			case "DOWNLOAD-FILE":
			
				$codeFile=$_REQUEST["File"];
				$codeFolder=$_REQUEST["Folder"];
				
				$data=$objApi->FileInfo($codeFile);			
				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Download File</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>
					<a href="download.php?File=<?php echo $data->ID?>" class="button">Download</a>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
				</div>
				<?php
			
			break;	

			case "RENAME-FILE":
			
				$codeFile=$_REQUEST["File"];
				$codeFolder=$_REQUEST["Folder"];
				
				$data=$objApi->FileInfo($codeFile);	
				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Rename File</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>

					<form  method="post"  action="make.php?Action=RENAME-FILE&Folder=<?php echo $codeFolder?>&File=<?php echo $codeFile?>">
					  
					  <div class="nomtext">New name</div>
					  <input type="hidden" name="OldName" >
					  <input type="text" name="NewName" >
					  <br><br>
					  <a class="button" href="index.php?Folder=<?php echo $codeFolder?>">Go Back</a>	
					  <button type="submit" class="button" value="Enter" >Rename</button>				
					</form>
					<br>
							
				</div>
				<?php
			break;		
			
		}//switch	
	?>
		
	</div>
</div>

</body>
</html> 