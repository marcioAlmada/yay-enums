<?php

/* Bootstrap ******************************************************************/

ini_set('assert.exception', true);

include __DIR__ . '/vendor/autoload.php';

include 'yay://test.macro.php'; // import the test dsl
include 'yay://enum.macro.php'; // import the enums lang feature itself

return \yay_compile(__FILE__); __halt_compiler();

/* Specs **********************************************************************/

declare(strict_types=1);

namespace YayPlayground;

enum Month {
    January,
    February,
    March,
    April,
    May,
    June,
    July,
    August,
    September,
    October,
    November,
    December
}

test 'Enum types must be classes' {
    assert(class_exists(Month::class));
}

test 'Enum fields should be objets of their respective types' {
    assert(is_object(Month::January));
}


test 'Enum fields should work with instanceof' {
    $month = Month::January;
    assert($month instanceof Month);
}

test'Enum types should work as argument types' {
    (function(Month $month) {})(Month::February);
}

test 'Enum fields should work with switch/case' {
    switch (Month::March) {
        case Month::February:
            assert(false);
            break;
        case Month::March:
            assert(true);
            break;
        default:
            assert(false);
            break;
    }
}

test 'Enum instances must stringify to their respective field name' {
    assert("March" === (string) Month::March);
    assert("March" === '' . Month::March);
}

/**
 * Expected to fail because it's impossible to have a reliable macro that can
 * intercept and analyze every 'constant(·expression())'. At least not without
 * sacrificing all fluffy bunnies.
 */
test xfail 'Enum instance must be accessible with "constant()"' {
    assert(Month::March === @constant(Month::class . '::March'));
}

test 'Enum access runtime interception must not affect class constant access' {
    class NotEnum { const NotEnumField = 1; }
    assert(NotEnum::NotEnumField === 1);
}

test 'Access to undefined enum fields should fail' {
    try {
        Month::Juno;
    }
    catch(\Error $e) {
        assert($e->getMessage() === "Undefined enum field 'YayPlayground\Month::Juno'");
        return;
    }
    assert(false);
}
