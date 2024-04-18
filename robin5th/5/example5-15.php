<?php


// starting with PHP 5, it's recommended to use the __construct() method for constructors. This new approach is more consistent and object-oriented, and it's also necessary for compatibility with PHP 7 and later, where the old-style constructors are deprecated for classes in namespaces.
class User
{
  public $param1;
  public $param2;

  function __construct($param1, $param2)
  {
    $this->param1 = $param1;
    $this->param2 = $param2;
  }
}
$testUser = new User("Param1", "Param2");
echo $testUser->param1, " ", $testUser->param2;
