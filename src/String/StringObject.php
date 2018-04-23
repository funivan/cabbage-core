<?php

  declare(strict_types=1);

  namespace Funivan\Cabbage\String;

  class StringObject implements StringInterface {

    /**
     * @var string
     */
    private $value;


    public function __construct(string $name) {
      $this->value = $name;
    }


    final public function value(): string {
      return $this->value;
    }

  }