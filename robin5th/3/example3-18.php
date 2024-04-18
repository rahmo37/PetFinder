<?php
  static $int = 0;         // Allowed 
  static $int = 1+2;       // Disallowed (will produce a Parse error)
  static $int = sqrt(144); // Disallowed

  // when declaring a static variable, it must be initialized with a constant value, not an expression that needs to be computed. This is because the expression would require computation at runtime, and static variables are set up before runtime execution begins. That's why $int = 0; is allowed, but $int = 1+2; and $int = sqrt(144); are not
?>
