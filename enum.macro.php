<?php

if (defined('T_ENUM')) return; // bail out in case native enums are available

return \yay_compile(__FILE__); __halt_compiler();

declare(strict_types=1);

namespace Yay\Enums;

interface Enum
{
}

function enum_field_or_class_constant(string $class, string $field)
{
    return
        (\in_array(\Yay\Enums\Enum::class, \class_implements($class))
            ? $class::$field()
            : \constant("{$class}::{$field}"))
    ;
}

macro ·global ·unsafe {
    // the enum declaration
    enum T_STRING·name · {
        ·ls
        (
            ·word()·field
            ,
            ·token(',')
        )
        ·fields
    }
} >> {
    class T_STRING·name implements \Yay\Enums\Enum {
        private static $store;

        private function __construct() {}

        static function __callStatic(string $field, array $args) : self {
            if(! self::$store) {
                self::$store = new \stdclass;
                ·fields ··· {
                    self::$store->·field = new class extends T_STRING·name {
                        function __debugInfo(){
                            return [T_STRING·name::class => ··stringify(·field)];
                        }

                        function __toString(){
                            return ··stringify(·field);
                        }
                    };
                }
            }

            if (isset(self::$store->$field)) return self::$store->$field;

            throw new \Error("Undefined enum field '" . __CLASS__ . "::{$field}'");
        }
    }
}

macro ·global {
    // sequence that matches the enum field access syntax:
    ·ns()·class // matches a namespace
    :: // matches T_DOUBLE_COLON used for static access
    ·not(·token(T_CLASS))·_ // avoids matching ::class resolution syntax
    ·word()·field // matches the enum field name
    ·not(·token('('))·_ // avoids matching static method calls
} >> {
    \Yay\Enums\enum_field_or_class_constant(·class::class, ··stringify(·field))
}
