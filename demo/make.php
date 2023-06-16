<?php
// ----------------------------------------------------------------------------------------------------------------------------
// PHP for testing Dataprius API v2.0
// ----------------------------------------------------------------------------------------------------------------------------
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
<h1 style="text-align:center;">Demo Test API Dataprius Cloud v.2.0</h1>

<div class="centercontent">
	<div class="whitebox" >
	<?php

		$action=isset($_REQUEST["Action"]) ? $_REQUEST["Action"] : "";
		
		switch($action)
		{
			case "CREATE-FOLDER":
						
				$codeFolder=$_REQUEST["Folder"];				
				$name=htmlspecialchars($_REQUEST["FolderName"]);
				
				$okCreated=$objApi->CreateFolder($codeFolder,$name);
				//$res=json_decode($jsonRes);
				$title = ($okCreated) ? "Folder Created." : "The folder already exists.";
				
				?>
					<div class="center">
					<h1 class="title"><?php echo $title;?></h1><br>
						<p class="nomtext"><?php echo $name; ?></p>
					<br>
					
					<a href="index.php?Folder=<?php echo $codeFolder;?>" class="button">Go Back</a>
					</div>
				<?php
			break;
			
			case "RENAME-FOLDER":
				
				$codeFolder=$_REQUEST["Folder"];
				$oldName=$_REQUEST["OldName"];
				$newName=$_REQUEST["NewName"];
				
				$res=$objApi->FolderRename($codeFolder,$newName);
				
				?>
					<div class="center">
					<h1 class="title">Rename Folder</h1><br>
						<p class="nomtext">Old Name: <b><?php echo $oldName ?></b></p>
						<p class="nomtext">New Name: <b><?php echo $newName ?></b></p>
					<br>
					<p class="nomtext"><?php echo $res? "Folder has been renamed successfully":"Failed renaming folder"; ?></p>
					<br>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
					</div>
				<?php
			break;
			
			case "DELETE-FOLDER":
				
				$codeFolder=$_REQUEST["Folder"];
				$codeParent=$_REQUEST["Parent"];
				$res=$objApi->FolderDelete($codeFolder);
				
				?>
					<div class="center">
					<h1 class="title">Delete Folder</h1><br>					
					<br>
					<p class="nomtext"><?php echo $res? "Folder has been deleted successfully":"Could not delete folder"; ?></p>
					<br>
					<a href="index.php?Folder=<?php echo $codeParent?>" class="button">Go Back</a>
					</div>
				<?php
			break;
			
			case "DELETE-FILE":
			
				$codeFile=$_REQUEST["File"];
				$codeFolder=$_REQUEST["Folder"];
				
				$data=$objApi->FileInfo($codeFile);			
				$res=$objApi->FileDelete($codeFile);
				
				?>
					<div class="center">
					<h1 class="title">Delete File</h1><br>
						<p class="nomtext"><?php echo $data->Name ?></p>
					<br>
					<?php echo $res? "File has been deleted successfully":"Failed deleting file"; ?>
					<br>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
					</div>
				<?php
			break;
			
			case "RENAME-FILE":
				
				$codeFile=$_REQUEST["File"];
				$codeFolder=$_REQUEST["Folder"];
				
				$oldName=$_REQUEST["OldName"];
				$newName=$_REQUEST["NewName"];
				
				$res=$objApi->FileRename($codeFile,$newName);
				
				?>
					<div class="center">
					<h1 class="title">Rename File</h1><br>
						<p class="nomtext">Old Name:<?php echo $oldName ?></p>
						<p class="nomtext">New Name:<?php echo $newName ?></p>
					<br>
					<?php echo $res? "File has been renamed successfully":"Failed renaming file"; ?>
					<br>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
					</div>


			<?php		
			break;		
			
			case "UPLOAD-FILE":
			
				$codeFolder=$_REQUEST["Folder"];
				
				$targetDir = "uploads/" . $codeFolder . "/";
				$maxSize = 20*1024*1024; // 20Mb
				
				$errorMsg = "Sorry, there was an error uploading your file.";
				$sucessMsg= "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
				$jsonResponse="";
				$data=null;
				
				if(!is_dir($targetDir))
				{
					mkdir($targetDir, 0755);				
				}
							
				if ($_FILES["fileToUpload"]["size"] > $maxSize)
				{
					echo "Sorry, your file is too large.";
					return;
				}			
				
				$targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
				$onLocalPath=false;
				
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))
				{
					$onLocalPath=true;	
				}
				
				$status = "error";
				
				if($onLocalPath)
				{
					$jsonResponse = $objApi->Upload($codeFolder,$targetFile);
					$status = $jsonResponse->status;
					if($status=="ok")
					{
						$data=$jsonResponse->data;
					}
				}
				
				?>
				<div class=" centercontent graybox">
					<h1 class="title">Upload File</h1><br>
					<p class="nomtext"><?php echo ($status=="ok")? "File uploaded successfully":"Failed to upload file"; ?></p>
					<p class="nomtext"><?php echo ($status=="ok")? $data->Name : ""; ?></p>
					<br><br>
					<a href="index.php?Folder=<?php echo $codeFolder?>" class="button">Go Back</a>
				</div>				
				<?php		
			break;		
			
		}//switch	
	?>
		
	</div>
</div>

</body>
</html> 