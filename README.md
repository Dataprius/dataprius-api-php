# Dataprius API PHP v.2
PHP client for the Dataprius API v.2
## Requirements
    PHP 5.3 or higher
    cURL
    Dataprius Account
### Getting Started
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



# Demo
The demo folder contais a dirty implementation using the class. This demo is intended to show class methods in simple manner. In few time you can build a folders browser app allowing files and folders operations.

[Dataprius downloads in english]: <https://dataprius.com/en/downloads>
[Dataprius downloads in spanish]: <https://dataprius.com/descargas>


