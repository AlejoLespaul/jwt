# JWT For Laravel 

## Installation
```
composer require alejo-lespaul/jwt
```
## Configuration Key
#### Add in .env file the next key:
```
JWT_SERVICE_KEY="ASecretKey"
```
## How use it

#### Create Token
```

$token = Jwt::signIn([
	"id" => 1
	"username" => "john wick"
	...
]);

# Return a valid token
```

#### Get Data From Token
```
$data = Jwt::getData($token);
# Return an array or throw an exception if the token is not valid
```

#### Ask if a token is valid
```
$boolean = Jwt::isValid($token);
# Return true o false
```

## Middleware
#### Add in app\Http\kernel.php:
```
use Jwt\Http\Middleware\JwtMiddleware;
...

    protected $routeMiddleware = [
        ...
        'jwt' => JwtMiddleware::class
    ];
```

* The middleware put the dataToken in the request

```
  $request->input('dataToken');
```
