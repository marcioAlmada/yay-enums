<?php

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
    assert(Month::January instanceof Month);
}

test'Enum types should work as argument types' {
    (function(Month $month) { assert($month === Month::February); })(Month::February);
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

test 'Enum instance must be accessible with "constant(<expression>)"' {
    assert(Month::March === constant(Month::class . '::March'));
    assert(Month::March === constant('\YayPlayground\Month::March'));
    assert(Month::March === constant('YayPlayground\Month::March'));
}

test 'Enum access runtime interception must not affect class constant and static method access' {
    class NotEnum {
        const NotEnumField = 1;
        static function NotEnumField() { return 2; }
    }

    assert(NotEnum::NotEnumField === 1);
    assert(NotEnum::NotEnumField() === 2);
}

test 'Enum access runtime interception must not affect access class members with semi reserved name' {
    class ClassWithSemiReservedNames {
        const constant = 'foo';
        static function constant(){ return 'bar'; }
    }

    assert(ClassWithSemiReservedNames::constant === 'foo');
    assert(ClassWithSemiReservedNames::constant() === 'bar');
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
