# Dataprius API PHP v.2
PHP client for the Dataprius API v.2
## Requirements
    PHP 5.3 or higher
    cURL
    Dataprius Account
# Getting Started
You will need a Dataprius account and API keys.
If you do not have a Dataprius account, go to the website, download and install the free version. You can download from the web in english or spanish.
- [Dataprius downloads in english]
- [Dataprius downloads in spanish]

Once the application is installed. Go to the button in the lower left corner.
- Click Start -> Configuration -> API Keys

There you can request API keys. There is a activation process. Dataprius will send you an email when your request have been validated and your keys are available.
After that, you can see API Keys on the same window in Configuration.

### Create a connection
<pre><code>$objApi = new DatapriusApi(API_CLIENT_ID_KEY,API_CLIENT_SECRET_KEY);</code></pre>
### Listing folders on root
First parameter is folder id, second paramer is page number. Root folder is an special case, it has the zero code.
<pre><code>$json=$objApi->ListFolders("0",1);</code></pre>
The response is a json object that corresponds to something like that:
<pre>
    {
    "status": "ok",
    "data": [
        {
            "ID": "X3lXnB",
            "Parent": "0",
            "Name": "Administration",
            "Path": "Administration"
        },       
        {
            "ID": "6GlAKy",
            "Parent": "0",
            "Name": "Proyects",
            "Path": "Proyects"
        }
    ],
    "meta": {
        "pagination": {
            "total_pages": 1,
            "current_page": "1",
            "total_results": 2
        }
    }
}
</pre>
### Listing subfolders inside folder
First parameter is folder id, second paramer is page number.
<pre><code>$json=$objApi->ListFolders("X3lXnB",1);</code></pre>
Note that the parent folder is the root code for those folders. This allows you to implement the folder tree by making requests to subfolders.
<pre>
    {
    "status": "ok",
    "data": [
        {
            "ID": "gLw847",
            "Parent": "X3lXnB",
            "Name": "Invoices",
            "Path": "Administration/Invoices"
        },       
        {
            "ID": "aZ3ghk",
            "Parent": "X3lXnB",
            "Name": "Shared documents with group",
            "Path": "Administration/Shared documents with group"
        }
    ],
    "meta": {
        "pagination": {
            "total_pages": 1,
            "current_page": "1",
            "total_results": 2
        }
    }
}
</pre>

## Handling erros
All requests to the api return a status field. You can check it in the php the class.
The php class will throw an exception if an error has occurred.
The internal format of an error json response is as follows
<pre>{"status":"error","data":null,"error":{"code":400,"error_type":"FOLDER_EXISTS","message":"Error creating folder: `MyFolder`. The folder already exists."}}</pre>
For example, you can get error information from an **Exception**.
<pre><code>
try
{
    $objApi->CreateFolder($code,$name);
}
catch(Exception $e)
{
    // Example getting error info.					
    echo "This is the exception Message:" . $e->getMessage() . "<br>";
    $json=json_decode($e->getMessage());
    echo "This is the Json response message: " . $json->error->message;
}    
</code></pre>
Displays something like that:
<pre>
    <b>This is the exception Message:</b>    
    {"status":"error","data":null,"error":{"code":400,"error_type":"FOLDER_EXISTS","message":"Error creating folder: `MyFolder`. The folder already exists."}}
    <b>This is the Json response message:</b>
    Error creating folder: `MyFolder`. The folder already exists. 
</pre>

# Operations with Folders

| Operation  | comments |
| ------------- | ------------- |
| ListFolders($code,$page)  |   |
| FolderInfo($code)  |  |
| CreateFolder($parentFolder,$name)  | Throws an error if the folder already exists. Forbidden chars in Windows are not allowed. |
| FolderRename($code,$newName)  | Throws an error if a folder with same name already exists. Forbidden chars in Windows are not allowed. |

Forbidden chars in Windows
<pre>
    < (less than)
    > (greater than)
    : (colon)
    " (double quote)
    / (forward slash)
    \ (backslash)
    | (vertical bar or pipe)
    ? (question mark)
    * (asterisk)
</pre>


# Demo
The demo folder contais a dirty implementation using the class. This demo is intended to show class methods in simple manner. In few time you can build a folders browser app allowing files and folders operations.

[Dataprius downloads in english]: <https://dataprius.com/en/downloads>
[Dataprius downloads in spanish]: <https://dataprius.com/descargas>


