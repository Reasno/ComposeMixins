# ComposeMixins
ComposeMixins is a tiny but extendable PHP library for function composition. 

## installation
```bash
# in your project root
composer install reasno/compose-mixins
```

## Examples
```php
use function Reasno\Helpers\composeMixins;
use Reasno\Helpers\Recipe;

$c = composeMixins(new Recipe('pipe'), /* callable */ $a, /* callable */ $b);
$result = $c($input); //Use the composed function

```

$c becomes a composition of function $a and function $b. You can verify it with the following snippet.

```php
$result1 = $c($input);
$result2 = $a($b($input));

var_dump($result1 === $result2); //true
```

## API
```function composeMixins(Recipe $recipe, callable ...$mixins) ```

## Recipes
This library comes with a few buildin recipes. These recipes define how functions are composed together.

* pipe: h(x) = g(f(x))
* map: h(list) =  array_map(g, (array_map(f, list))) 
* collapse: h(cube) =  array_reduce((array_reduce(cube, f), g)) 
* all: returns true if all functions return true.
* any: returns true if any function returns true.

To use any recipe, create new instance with their names.
```php
new Recipe('pipe');
```

You can create new recipes on the fly. Just pass in a closure.
```php
Recipe::fromCallable(function(...$fns){
	$i = 0;
	return function($input) use (&$i, $fns){
		try{
			return $fns[$i++]($input);
		} catch( OutOfBoundsException $e ){
			return null;
		}

	};
});
```

You can define your own RecipeHandlers in your own code, and pass it to the recipe constructor.
```php
Class MyRecipeHandler{
	//...
}
$CustomRecipe = new Recipe('fancy', ['handler' => MyRecipeHandler']);
```
Please take a look at [RecipeHandler.php](https://github.com/Reasno/ComposeMixins/blob/master/src/RecipeHandler.php) in this library to learn how to write your own handlers.

## Caveat
Functions composed with map and collapse recipes also accept Traversable as input.

