<?php
namespace Reasno\Helpers;

function composeMixins(Recipe $recipe, callable ...$mixins) 
{
	return function(
		$x = [], 
		callable $recipeHandler = null
	) use ( $recipe, $mixins ){
		if ($recipeHandler === null) {
			$recipeHandler = $recipe->handler;
		}
		$func = call_user_func_array($recipeHandler,  $mixins)->bindTo($recipe);
		return $func($x);
	};
}

