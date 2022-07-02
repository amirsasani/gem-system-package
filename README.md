# Gem System package
A package to manage users gem in laravel

## Usage

Get a user to manage their gems:
```php
$user = User::find(1);
```

Set the user to the `GemService` Facade
```php
use AmirSasani\GemSystem\Facades\GemService;

$gemService = GemService::setUser($user);
```

To increment gem
```php
$gemService->increment(10);
```

To decrement gem
```php
$gemService->decrement(-3);
```

To get user gem model
```php
$gemService->getGem();
```

All together
```php
$gemService = GemService::setUser($user)->increment(10)->decrement(-3)->getGem();
```