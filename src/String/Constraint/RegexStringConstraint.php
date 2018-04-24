<?php

  declare(strict_types=1);

  namespace Funivan\CabbageCore\String\Constraint;

  class RegexStringConstraint implements StringConstraintInterface {

    /**
     * @var string
     */
    private $regex;


    public function __construct(string $regex) {
      $this->regex = $regex;
    }


    public function valid(string $string): bool {
      return preg_match($this->regex, $string) === 1;
    }
  }