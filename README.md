# Bot conversa api client (WIP)

SDK for integration with [Bot conversa](https://app.botconversa.com.br/)

## Requirements
- php 7.4
- curl
- api key

## Installation

You can install the package via composer:

```bash
$ composer require papalardo/bot-conversa-api-client
```

## Configuration

Set configuration using singleton class:

```php
use Papalardo\BotConversaApiClient\BotConversaClientConfig;

BotConversaClientConfig::i()
    ->accessToken('your-access-token')
    ->debug() // To enable debug info
    ;
```

## Available features
Im developing this package for my needs, so only some features were created like:

- Read subscriber
- Create subscriber
- Send message subscriber

Having needs we will develop more features. 

This sdk was grouped by modules as noted in [documentation](https://backend.botconversa.com.br/swagger/)

So, to use ```subscriber``` module, make like this:

```php
use Papalardo\BotConversaApiClient\BotConversaClient;

$subscriberService = BotConversaClient::make()->subscriber();

// Creating subscriber
$subscriberService->create(new CreateSubscriberData([
    'phone' => '5561000000000',
    'firstName' => 'John',
    'lastName' => 'Doe'
]))

// Read subscriber
$subscriber = $subscriberService->read('5561000000000');

// Send message
$subscriberService
    ->sendMessage($subscriber->id(), new SendMessageData([
        'value' => 'Notification test message'
    ]));
```

### Notes
- This package be using DTO Pattern, then the code must be your documentation. You will see the payload for request in DTO classes

## Credits

- [papalardo](https://github.com/papalardo)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
