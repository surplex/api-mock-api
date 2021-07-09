# <a id="title" href="#">Surplex API Mock</a>
---  

API for "the software to mock third-party responses" to include in your application's API tests.
&nbsp;
&nbsp;

## Use Proxy / Automatic Mode
To automatically forward requests to the API mock there is the possibility to use a `HandlerStack` for `Guzzle`.
The `HandlerStack` checks if a request has already been saved and if not,
the request will be sent to the correct host and then the response will be cached,
so that the next time a request will be sent to the API mock, and the cached response will be returned.

```php
<?php
//...
$apiMockClient = new \GuzzleHttp\Client([/*...*/]);
$apiMock = new \ApiMock\Core\ApiMock($apiMockClient);
$proxyStack = new \ApiMock\Proxy\ProxyStack($apiMock);

$myDefaultClient = new \GuzzleHttp\Client([
    /*...*/
    'handler' => $proxyStack,
]);

// Will be sent against example.org
$myDefaultClient->get('https://example.org', []);
// Will be sent against API Mock
$myDefaultClient->get('https://example.org', []);
```


## <a id="Troubleshooting" href="#Troubleshooting">Troubleshooting</a>
---  
#### I used a requestKey, but the `ApiMock::getClientRequest()` gets a 404/null
**A)** The request in question has never been called by your application, therefore a request with the requestKey has never
been recorded. Make sure to `ApiMock::addResponse()` in the correct order. Maybe one of the earlier responses does not
match your application's excpectations and causes it to fail?  
**B)** Your requests to API Mock use different session keys (or none at all). For example, this can happen if parts of
your application run asyncronously (e.g. in queues). Use the $sessionCacheFile feature to retrieve the correct session
key. (provide a file path where API Mock can dump the currently used session key into and then retrieve it in your
application).
