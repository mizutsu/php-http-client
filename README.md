# What is this?
This is simple HTTP client developed with PHP. <br>
You can set [options for cURL](https://www.php.net/manual/en/function.curl-setopt.php) by yourself. 


# Requirement
PHP7.4+

# Reference for this lib
## Regarding class method
| PHP Method          | Param | Return | throw Exception |
| ------------------- | ------| ---- | :----: |
| setCurlOptions()    | `array` <br> Regarding details please refer to sample. | `void` | ○ |
| execute()           | -     | `string` | ○ |
| getResponseCode()   | -     | `null` \| `int` | - |
| getResponseDetails()| -     | `array` <br> Regarding details please refer to sample. | ○ |


## Regarding Exception
| Exception field   | Description |
| ------------------| ----------- |
| Exception message | format <br> `curl_errno : %d curl_error_message : %s` |
| Exception code    | curl_errno is set as Exception code                      |

[curl_errno](https://curl.haxx.se/libcurl/c/libcurl-errors.html) <br>
[curl_error_message](https://www.php.net/manual/en/function.curl-error.php)   

## How to catch timeout
```
<?php
...

try {
   ...
} catch (\Exception $e) {
    switch($e->getCode) {
        // timeout
        case CURLE_OPERATION_TIMEOUTED:
            // process for timeout.
            break;
        default:
            break;
    }
}
```


## Sample
```
<?php
require_once 'C:\Users\mizutsu\Desktop\php-http-client\src\HttpClient.php';

$httpClient = new Mizutsu\Lib\HttpClient();

$auth = sprintf('Authorization : Bearer %s', getenv('QIITA_API_TOKEN'));
// Regarding structure of $options array, please be same with options array to set to curl_setopt_array(). 
// @see https://www.php.net/manual/en/function.curl-setopt-array.php
$options = [
    CURLOPT_HTTPHEADER => [$auth],
    CURLOPT_URL => 'https://qiita.com/api/v2/items',
];
$httpClient->setCurlOptions($options);

$responseBody = $httpClient->execute();

$httpCode = $httpClient->getResponseCode();

// Return value from getResponseCode() is same with curl_getinfo($ch).
// @see https://www.php.net/manual/en/function.curl-getinfo.php#refsect1-function.curl-getinfo-returnvalues
$responseDetails = $httpClient->getResponseDetails();
```
