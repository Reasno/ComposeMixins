<?php
namespace Reasno\Helpers;

function composeMixins(callable ...$mixins)
{
	return function(
		$x = [], 
		callable $mix = null
	) use ($mixins){
		if ($mix === null) $mix = function(callable ...$fns) use ($x){
			return array_reduce($fns, function($acc, $fn){
				return call_user_func($fn, $acc);
			}, $x);
		};
		$func = call_user_func_array($mix,  $mixins);
		return $func;
	};
}