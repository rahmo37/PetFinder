<?php
// In PHP, class properties may be initialized with constant values (like strings, numbers, booleans) directly in their declaration, which is why the assignments to $name and $age are valid. However, properties cannot be initialized with expressions that involve function calls (like time()) or rely on the value of other properties or variables (as seen with $level * 2) directly in their declaration. This is because property declarations are evaluated at compile time, when the class is defined, and at that point, the PHP runtime cannot execute function calls or evaluate expressions that depend on runtime information or other variable properties.
class Test
{
  public $name     = "Paul Smith"; // Valid
  public $age      = 42;           // Valid
  public $time     = time();       // Invalid - calls a function
  public $score    = $level * 2;   // Invalid - uses an expression
}
