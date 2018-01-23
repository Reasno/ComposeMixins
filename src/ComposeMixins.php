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
		$func = call_user_func_array($recipeHandler,  $mixins);
		return $func($x);
	};
}

class RecipeHandler{

	private function pipe(callable ...$fns){
		return function($init) use ($fns){
			if ($this->reverseOrder){
				array_reverse($fns);
			}
			return array_reduce($fns, function($acc, $fn){
				if ($this->assignVars){
					return call_user_func_array($fn, $acc);
				}
				return call_user_func($fn, $acc);
			}, $init);
		};
	}

	private function chain(callable ...$fns){
		return function($init) use ($fns){
			if ($this->reverseOrder){
				array_reverse($fns);
			}			
			return array_reduce($fns, function($acc, $fn){
				if ($this->assignVars){
					return call_user_func_array(
						[ $acc[0], $fn ], 
						array_slice($acc, 1)
					);
				}
				return call_user_func([$acc, $fn]);
			}, $init);
		};
	}
	private function map(callable ...$fns){
		return function($init) use ($fns){
			if ($this->reverseOrder){
				array_reverse($fns);
			}
			if ($init instanceof Traversable){
				$first = iterator_to_array($init);
			} else {
				$first = (array)$init;
			}
			return array_reduce($fns, function($acc, $fn){
				if ($this->assignVars){
					return array_map($fn, ...$acc);
				}
				return array_map($fn , $acc);
			}, (array)$init);
		};
	}
	private function collapse(callable ...$fns){
		return function($init) use ($fns){
			if ($this->reverseOrder){
				array_reverse($fns);
			}
			if ($init instanceof Traversable){
				$first = iterator_to_array($init);
			} else {
				$first = (array)$init;
			}
			return array_reduce($fns, function($acc, $fn){
				if ($this->assignVars){
					return array_reduce($fn, ...$acc);
				}
				return array_reduce($fn , $acc);
			}, (array)$init);
		};
	}

}
class Recipe{
	public function __construct( $handlerName = null, $options = null){
		if ($options !== null){
			$this->assignVars = (bool)$options['assignVars'];
			$this->reverseOrder = (bool)$options['reverseOrder'];
		} else {
			$this->assignVars = false;
			$this->reverseOrder = false;
		}
		if ($handlerName === null){
			return;
		}
		$handler = new RecipeHandler;
		try {
			$method = new \ReflectionMethod( $handler, $handlerName );
		} catch(\ReflectionException $e){
			throw new \Error('Invalid RecipeHandler Name: '.$e->getMessage());
		}
		$method->setAccessible(true);
		$this->handler = \Closure::bind($method->getClosure($handler), $this);
	}

	public static function fromCallable(callable $fn){
		$r = new Recipe();
		$r->handler = $fn;
		return $r;
	}
}