<?php
// ---------------------------------------------------------------------------------------------------------------------
// Class Api Dataprius
// ---------------------------------------------------------------------------------------------------------------------
require_once("config.php");


class DatapriusApi
{	
	const AUTH_URL = 'oauth/token/';
	
	private $endPoint = "";
	private $clientId = "";
	private $clientSecret ="";
    public $bearerToken="";	
	
	public $display_errors=true;
	
	public function __construct($clientId, $clientSecret)
	{
		$this->endPoint=DEFAULT_END_POINT;		
		$this->clientId=$clientId;
		$this->clientSecret=$clientSecret;
			
		try
		{
			$this->Login();
		}
		catch(Exception $e)
		{
			echo("Api Error: "  . $e->getMessage());
			throw new Exception($e->getMessage());		
		}
	}
	
	// LOGIN
	// ----------------------------------------------------------------------------------------------------
	private function Login()
	{		
		try
		{
			$jsonRequest=$this->AuthRequest();
			$obj=json_decode($jsonRequest);
			//$this->bearerToken=$obj->access_token;
			if ($obj->status != "ok")
			{
				echo("Api login Error");
				throw new Exception("Login Error");
			}
			$this->bearerToken=$obj->data->access_token;
		}
		catch (Exception $e)
		{			
			throw new Exception($e->getMessage());
		}
	}
	
	// FOLDERS 
	// ----------------------------------------------------------------------------------------------------
	public function FolderInfo($codeFolder)
	{
		$pathURL = "/folders/info/" . "$codeFolder";
		
		try
		{
			$json = json_decode($this->ApiRequest($pathURL,"GET"));
			
			if($json->status=="ok")
			{
				return $json->data[0];
			}
			else
			{
				throw new Exception($json);
			}
		}
		catch (Exception $e)
		{			
			throw $e;
		}		
	}
	
	public function ListRootFolders()
	{
		$pathURL = "folders/list/" . "0";
		
		try
		{
			return $this->Request($pathURL);
		}
		catch (Exception $e)
		{
			throw $e;
		}		
	}
	
	public function ListFolders($codeFolder,$page)
	{
		$pathURL = "folders/list/" . "$codeFolder?Page=$page";
				
		try
		{			
			$json=json_decode($this->ApiRequest($pathURL,"GET"));
			
			if($json->status=="ok")
			{				
				//return $json->data;
				return $json;
			}
			else
			{
				throw new Exception($json);
			}
		}
		catch (Exception $e)
		{			
			throw $e;			
		}		
	}
	
	public function CreateFolder($parentFolder,$name)
	{
				
		$pathURL = "/folders/create/" . "$parentFolder";
		
		try
		{
			$data=array('Name' => $name);			
			$json = json_decode($this->ApiRequest($pathURL,"POST",$data));
			
			return ($json->status)=="ok";
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function FolderRename($codeFolder,$newName)
	{
		$pathURL = "/folders/rename/" . "$codeFolder";
		
		try
		{
			$data=array('NewName' => $newName);			
			$json = json_decode($this->ApiRequest($pathURL,"PATCH",$data));
			
			return ($json->status)=="ok";
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function FolderDelete($codeFolder)
	{
		$pathURL = "/folders/delete/" . "$codeFolder";
		
		try
		{					
			$json = json_decode($this->ApiRequest($pathURL,"DELETE",$data));
			
			return ($json->status)=="ok";
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	public function ListFiles($codeFolder,$page)
	{
		$pathURL = "/folders/files/" . "$codeFolder?Page=$page";
		
		try
		{			 
			$json=json_decode($this->ApiRequest($pathURL,"GET"));
			
			if($json->status=="ok")
			{				
				//return $json->data;
				return $json;
			}
			else
			{
				throw new Exception($json);
			}
		}
		catch (Exception $e)
		{			
			throw $e;
		}		
	}
	
	// ----------------------------------------------------------------------------------------------------
	// FILES 
	// ----------------------------------------------------------------------------------------------------
	public function FileInfo($codeFile)
	{
		$pathURL = "/files/info/" . "$codeFile";
		
		try
		{
			$json = json_decode($this->ApiRequest($pathURL,"GET"));
						
			if($json->status=="ok")
			{
				return $json->data;
			}
			else
			{
				throw new Exception($json);
			}
		}
		catch (Exception $e)
		{			
			throw $e;
		}		
	}
	
	public function FileDelete($codeFile)
	{
		$pathURL = "/files/delete/" . "$codeFile";
		
		try
		{
			$json = json_decode($this->ApiRequest($pathURL,"DELETE"));
			return ($json->status)=="ok";
			
		}
		catch (Exception $e)
		{
			throw $e;
		}		
	}	
	
	public function FileRename($codeFile,$newName)
	{
		$pathURL = "/files/rename/" . "$codeFile";
		
		try
		{
			$data=array('NewName' => $newName);			
			$json = json_decode($this->ApiRequest($pathURL,"PATCH",$data));
			
			return ($json->status)=="ok";
		}
		catch (Exception $e)
		{
			throw $e;
		}		
	}
	
	public function Upload($codeFolder,$localFilePath)
	{
		$pathURL = "/files/upload/";
		
		try
		{
			$json=$this->RequestUpload($pathURL,$codeFolder,$localFilePath);
			$res=json_decode($json);
			return $res;
		}
		catch (Exception $e)
		{
			throw $e;
		}		
	}
	
	public function Download($codeFile)
	{
		$pathURL = "/files/download/"."$codeFile";
		
		try
		{
			echo $this->RequestDownload($pathURL,$codeFile);
		}
		catch (Exception $e)
		{
			throw $e;
		}		
	}
	
	
	// Request to API 
	// ----------------------------------------------------------------------------------------------------	
	private function AuthRequest()
	{				
		$authBasic = base64_encode($this->clientId . ":" . $this->clientSecret);
				
		$curl = curl_init( $this->endPoint . self::AUTH_URL );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded;charset=UTF-8');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic " . $authBasic));
						
        $response = curl_exec($curl);
		$httpCode=curl_getinfo($curl, CURLINFO_HTTP_CODE);	
		curl_close($curl);
		
		if($httpCode==200)
		{		
			return $response;
		}
		else
		{			
			throw new Exception($response);
		}
	}

    private function ApiRequest($pathURL,$verb,$arrayData=null)
	{				
		try
		{		
			$curl = curl_init( $this->endPoint . $pathURL);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);			
			
			if($arrayData!=null&& count($arrayData)>0 )
			{				
				$jsonData=json_encode($arrayData);
								
				$headers=array();
				$headers[]="Content-Type: application/json";
				$headers[]="Authorization: Bearer " . $this->bearerToken;
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	
			}
			else
			{
				curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->bearerToken));
			}
							
			$response = curl_exec($curl);		
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);										
			curl_close($curl);
			
			if($httpCode==200)
			{
				return $response;
			}
			else
			{			
				if($this->display_errors) echo $response;			
				throw new Exception($response);
			}	
		}
		catch(Exception $e)
		{			
			if($this->display_errors) echo $e;
		}
	}	
	
	private function Request($pathURL)
	{				
		$curl = curl_init( $this->endPoint . $pathURL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');			
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->bearerToken));
						
        $response = curl_exec($curl);		
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);										
		curl_close($curl);
		
		if($httpCode==200)
		{
			return $response;
		}
		else
		{			
			if($this->display_errors) echo $response;			
			throw new Exception($response);
		}		
	}
	
	private function RequestUpload($pathURL,$codeFolder,$localFilePath)
	{				
		$curl = curl_init( $this->endPoint . $pathURL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

		$postData = array(
			'IDFolder' => $codeFolder,			
			'file' => new CURLFILE($localFilePath),
		);
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->bearerToken));
						
        $response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		if($httpCode==200)
		{
			return $response;
		}
		else
		{			
			if($this->display_errors) echo $response;			
			throw new Exception($response);
		}
	}
	
	private function RequestDownload($pathURL)
	{				
		$curl = curl_init( $this->endPoint . $pathURL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);			
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->bearerToken));
						
        $response = curl_exec($curl);
		curl_close($curl);
		
		if($httpCode==200)
		{
			return $response;
		}
		else
		{			
			if($this->display_errors) echo $response;			
			throw new Exception($response);
		}	
		
	}
	
	
}

?>