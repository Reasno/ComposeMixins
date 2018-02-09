<?php
require_once(__DIR__.'/../src/ComposeMixins.php');
require_once(__DIR__.'/../src/Recipe.php');
require_once(__DIR__.'/../src/RecipeHandler.php');
use function Reasno\Helpers\composeMixins;
use Reasno\Helpers\Recipe;

$a = function($x){
	return $x + 1;
};
$b = function($x){
	return $x * $x;
};
$c = function($x){
	return $x % 5;
};
$d = function($x){
	return true;
};
$e = function($x){
	return false;
};
$f1 = composeMixins(new Recipe('pipe'), $a, $b, $c);
assert($f1(3) === 1,  'Recipe pipe');
$f2 = composeMixins(new Recipe('map'), $a, $b, $c);
assert($f2([3,4]) === [1,0],  'Recipe map');
$f3 = composeMixins(new Recipe('any'), $d, $e);
assert($f3(5) === True,  'Recipe any');
$f4 = composeMixins(new Recipe('all'), $d, $e);
assert($f4(5) === False,  'Recipe all');


$r = Recipe::fromCallable(function(...$fns){
	static $i = 0;
	return function($input) use (&$i, $fns){
		try{
			return $fns[$i++]($input);
		} catch( Error $e ){
			return null;
		}

	};
});
$f5 = composeMixins($r, $a, $b, $c);
assert($f5(5) === 6, 'Recipe from callable');
assert($f5(5) === 25, 'Recipe from callable');
assert($f5(5) === 0, 'Recipe from callable');
assert($f5(5) === null, 'Recipe from callable');