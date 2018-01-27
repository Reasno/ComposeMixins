<?php
namespace Reasno\Helpers;
class RecipeHandler{

	private function pipe(callable ...$fns){
		return function($init) use ($fns){
			return array_reduce($fns, function($acc, $fn){
				return call_user_func($fn, $acc);
			}, $init);
		};
	}

	private function map(callable ...$fns){
		return function($init) use ($fns){
			if ($init instanceof Traversable){
				$first = iterator_to_array($init);
			} else {
				$first = (array)$init;
			}
			return array_reduce($fns, function($acc, $fn){
				return array_map($fn , $acc);
			}, $first);
		};
	}

	private function any(callable ...$fns){
		return function($init) use ($fns){
			return array_reduce($fns, function($acc, $fn){
				return $acc || $fn($acc);
			}, $init);
		};
	}

	private function all(callable ...$fns){
		return function($init) use ($fns){
			return array_reduce($fns, function($acc, $fn){
				return $acc && $fn($acc);
			}, $init);
		};
	}
}