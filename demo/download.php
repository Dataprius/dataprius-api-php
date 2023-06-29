<?php
// ----------------------------------------------------------------------------------------------------------------------------
// PHP for testing Dataprius API v2.0
// ----------------------------------------------------------------------------------------------------------------------------
require_once("../dataprius-api.php");

$codeFile=$_REQUEST["File"];

$objApi = new DatapriusApi(API_CLIENT_ID,API_CLIENT_SECRET);

$data=$objApi->FileInfo($codeFile);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="'.basename($data->Name).'"');
$objApi->Download($codeFile);

?>
