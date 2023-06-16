<?php
// ----------------------------------------------------------------------------------------------------------------------------
// PHP for testing Dataprius API v2.0
// ----------------------------------------------------------------------------------------------------------------------------
require_once("../dataprius-api.php");

// Params
// ----------------------------------------------------------------------------
$codeFolder= isset($_REQUEST["Folder"]) ? $_REQUEST["Folder"] : "0";
$codeParent= isset($_REQUEST["Parent"]) ? $_REQUEST["Parent"] : "0";

$objApi = new DatapriusApi(API_CLIENT_ID,API_CLIENT_SECRET);
//$bearerToken=$objApi->bearerToken;

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
	<div class="headbox" >	
	<?php
			
			// Folder path
			// ---------------------------------------------
			$path = "";
			$idFolder = 0;
			$idParent = 0;
			if ($codeFolder!="0")
			{
				$folderInfo=$objApi->FolderInfo($codeFolder);
				$path = $folderInfo->Path;
				$idFolder = $folderInfo->ID;
				$idParent = $folderInfo->Parent;
			}
		?>
		
		
		<div class="folder">
						
			<div class="folder-actions">
				<a class="icon24 ico-add-folder" title="Create folder" href="actions.php<?php echo "?Folder=".$idFolder."&Action=CREATE-FOLDER"?>"></a>
				<?php
				if ($codeFolder!="0")
				{
					?>
					<a class="icon24 ico-delete" title="Delete folder" href="actions.php<?php echo "?Folder=".$idFolder."&Parent=".$idParent."&Action=DELETE-FOLDER"?>"></a>
					<a class="icon24 ico-rename" title="Rename folder" href="actions.php<?php echo "?Folder=".$idFolder."&Action=RENAME-FOLDER"?>"></a>
					<a class="icon24 ico-upload" title="Upload file to folder" href="actions.php<?php echo "?Folder=".$idFolder."&Action=UPLOAD-FILE"?>"></a>
				<?php
				}
				?>
					
			</div>
			<?php
			if ($codeFolder!="0")
			{
			?>
				<a class="iconHead icotop"  href="index.php?Folder=0"></a>
			<?php
			}
			?>
			
			<div class="folderPath"><?php echo $path;?></div>
		</div>

	</div>
</div>

<div class="centercontent">
	<div class="whitebox" >
		
		
		<?php
		   // Perent folder in folders tree
		   // -----------------------------------------------
		   if($codeFolder!="0")
		   {
		?>
			<a class="linkfolder" href="index.php?Folder=<?php echo $codeParent?>">
			<div class="folder">
				<div class="icon icosup"></div>
				<div class="name">Up</div>
			</div>
			</a>		
		<?php
		   }
		?>	
		
		<?php
			
			// Folders under the folder with this code 
			// ---------------------------------------------		
			$json=$objApi->ListFolders($codeFolder,1);
			$totalPages=1;
			// pagination
			if (count($json->data)>0)
			{
				$totalPages=$json->meta->pagination->total_pages;
			}
			$data = $json->data;
			
			foreach($data as $item)
			{
		?>
			<div class="folder">				
				<a href="index.php?Folder=<?php echo $item->ID."&Parent=".$item->Parent;?>"><div class="icon ico-folder"></div></a>
				<a class="linkfolder" href="index.php?Folder=<?php echo $item->ID."&Parent=".$item->Parent;?>"><div class="name"><?php echo $item->Name; ?></div></a>			
			</div>
			
		<?php
			}
			
			if ($totalPages>1)
			{
				for ($i=2;$i<=$totalPages;$i++)
				{
					$json=$objApi->ListFolders($codeFolder,$i);
					$data = $json->data;
					foreach($data as $item)
					{
		?>
					<div class="folder">				
						<a href="index.php?Folder=<?php echo $item->ID."&Parent=".$item->Parent;?>"><div class="icon ico-folder"></div></a>
						<a class="linkfolder" href="index.php?Folder=<?php echo $item->ID."&Parent=".$item->Parent;?>"><div class="name"><?php echo $item->Name; ?></div></a>			
					</div>
					
		<?php
					}
				}
			}
		?>
		
		
		<?php
			
			// Files under the folder with this code 
			// ---------------------------------------------		
			$json=$objApi->ListFiles($codeFolder,1);
			
			$totalPages=1;
			// pagination
			if (count($json->data)>0)
			{
				$totalPages=$json->meta->pagination->total_pages;
			}
			$data = $json->data;
			
			foreach($data as $item)		 
			{
		?>		
			<div class="file">
				<div class="file-actions"> 
					<a class="icon24 ico-delete" title="Delete file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=DELETE-FILE"?>"></a>
					<a class="icon24 ico-rename" title="Rename file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=RENAME-FILE"?>"></a>
					<a class="icon24 ico-download" title="Download file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=DOWNLOAD-FILE"?>"></a>				
				</div>
				<div class="icon ico-file"></div>
				<div class="name"><?php echo $item->Name; ?>  </div>			
			</div>		
		<?php
			}
			
			if ($totalPages>1)
			{
				for ($i=2;$i<=$totalPages;$i++)
				{
					$json=$objApi->ListFiles($codeFolder,$i);
					$data = $json->data;
					foreach($data as $item)		 
					{
		?>		
			<div class="file">
				<div class="file-actions"> 
					<a class="icon24 ico-delete" title="Delete file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=DELETE-FILE"?>"></a>
					<a class="icon24 ico-rename" title="Rename file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=RENAME-FILE"?>"></a>
					<a class="icon24 ico-download" title="Download file" href="actions.php<?php echo "?Folder=".$codeFolder."&File=".$item->ID."&Action=DOWNLOAD-FILE"?>"></a>				
				</div>
				<div class="icon ico-file"></div>
				<div class="name"><?php echo $item->Name; ?>  </div>			
			</div>		
		<?php
					}
				}
			}
		?>		
		
	</div>
</div>
</body>
</html> 