```php
use \S25\MegazipApiClient\{Client, Options};

$client = new Client('http://service.url');
// Или
$options = Options::new()->setLogger(/* LoggerInterface */)->setTrace(true);
$client = new Client('http://service.url', $options);
```