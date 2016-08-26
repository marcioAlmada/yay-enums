<?php

ini_set('assert.exception', true);

include __DIR__ . '/vendor/autoload.php';

include 'yay://test.macro.php'; // import the test dsl
include 'yay://enum.macro.php'; // import the enums lang feature itself
include 'yay://enum.tests.php'; // run the tests
