<?php
namespace Reasno\Helpers;
class Recipe{
	public function __construct( $handlerName = null, $options = null){
		if ($options !== null){
			$this->assignVars = (bool)$options['assignVars'];
			$this->reverseOrder = (bool)$options['reverseOrder'];
			$this->handlerClass = $options['handler'];
		} else {
			$this->assignVars = false;
			$this->reverseOrder = false;
			$this->handlerClass =  __NAMESPACE__."\\RecipeHandler";
		}
		if ($handlerName === null){
			return;
		}
		$handler = new $this->handerClass;
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