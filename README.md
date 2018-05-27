
# PHPCloc
:rocket: Cloc & duplicate code checker written in PHP

## Requirements
    PHP >= 5.5.9

## Installation
### Manual
```
$ wget https://github.com/appzcoder/phpcloc/releases/download/v0.0.2/phpcloc.phar -O phpcloc
// or
$ curl -L https://github.com/appzcoder/phpcloc/releases/download/v0.0.2/phpcloc.phar -o phpcloc
```
Then
```
$ sudo chmod a+x phpcloc
$ sudo mv phpcloc /usr/local/bin/phpcloc
```

### Composer
```
$ composer global require appzcoder/phpcloc
```

## Usage
### Cloc
```
$ phpcloc cloc .
```
<img width="614" alt="cloc" src="https://user-images.githubusercontent.com/1708683/40279910-0c5d093e-5c6d-11e8-86e8-d78d59a4acbe.png">

### Duplicate code checker
```
$ phpcloc duplicate . --ext=php
```
<img width="1287" alt="duplicate" src="https://user-images.githubusercontent.com/1708683/40583089-056da3ea-61a9-11e8-95ca-e68504d86338.png">

#### Available Commands
```
$ phpcloc cloc directory --ext=php,js --exclude=vendor,node_modules
```

```
$ phpcloc duplicate directory --ext=php --exclude=vendor
```

## Todo
- Improve algorithm complexity
- Testing

## Author

[Sohel Amin](http://sohelamin.com)

## License

This project is licensed under the MIT License - see the [License File](LICENSE) for details
