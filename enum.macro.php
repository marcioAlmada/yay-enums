<?php declare(strict_types=1);

namespace Yay\Enums;

if (defined('T_ENUM')) return; // bail out in case native enums are available

interface Enum
{
}

function enum_or_class_constant_dispatch(string $expr)
{
    if (false === strpos($expr, '::')) goto constant_op;

    list($class, $field) = explode('::', $expr);

    if (false === class_exists($class, true)) goto constant_op;

    if (false === (\in_array(\Yay\Enums\Enum::class, \class_implements($class)))) goto constant_op;

    return $class::{$field}();

    constant_op:

    return constant($expr);
}

macro ·global ·unsafe {
    // the enum declaration
    enum T_STRING·name · {
        ·ls
        (
            ·label()·field
            ,
            ·token(',')
        )
        ·fields
    }
} >> {
    class T_STRING·name implements \Yay\Enums\Enum {
        private static $store;

        final private function __construct() {}

        final static function __callStatic(string $field, array $args) : self {
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
    ·not(·token(T_CLASS))·_ // do not match ::class resolution syntax
    ·label()·field // matches the enum field name
    ·not(·token('('))·_ // do not match static method calls
} >> {
    \Yay\Enums\enum_or_class_constant_dispatch(·class::class . '::' . ··stringify(·field))
}

macro ·global {
    constant
    ·midrule(function($ts){
        $index = $ts->index(); // save current position

        $ts->previous();
        $token = $ts->previous();
        $skip = $token->is(T_OBJECT_OPERATOR, T_DOUBLE_COLON, T_FUNCTION, T_CONST);

        $ts->jump($index); // always backtrack

        return $skip ? null : new \Yay\Ast;
    })
    (···expr)
} >> {
    \Yay\Enums\enum_or_class_constant_dispatch(···expr)
}
