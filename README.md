# ComposeMixins
ComposeMixins is a tiny but extendable library for function composition. 

# Hello World
```php
$composed = Reasno\Helpers\composeMixins(new Recipe('pipe'), callable $a, callable $b);
$composed($input); //Use the composed function
```

# Warning
This library is still under developement. Do not use.