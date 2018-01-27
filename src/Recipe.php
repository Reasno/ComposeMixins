<?php
namespace Reasno\Helpers;
class Recipe{
	public function __construct( $handlerName = null, $options = null){
		if ($options !== null){
			$this->handlerClass = $options['handler'];
		} else {
			$this->handlerClass =  __NAMESPACE__."\\RecipeHandler";
		}
		if ($handlerName === null){
			return;
		}
		$handler = new $this->handlerClass;
		try {
			$method = new \ReflectionMethod( $handler, $handlerName );
		} catch(\ReflectionException $e){
			throw new \Error('Invalid RecipeHandler Name: '.$e->getMessage());
		}
		$method->setAccessible(true);
		$this->handler = $method->getClosure($handler);
	}

	public static function fromCallable(callable $fn){
		$r = new Recipe();
		$r->handler = $fn;
		return $r;
	}
}