<?php

macro ·global ·unsafe {
    test ·optional(xfail)·xfail T_CONSTANT_ENCAPSED_STRING·spec { ···body }
} >> {
    (function(){
        try {
            $x = strtoupper(··stringify(·xfail));
            $result = (false !== (function(){
                ···body
            })());

            if($result){
                if ($x !== 'XFAIL')
                    echo '✓ ' . T_CONSTANT_ENCAPSED_STRING·spec . '.', PHP_EOL;
                else
                    echo 'FAIL: ' . T_CONSTANT_ENCAPSED_STRING·spec . '.', PHP_EOL;
            }
            else {
                echo ($x ?: 'FAIL') . ': ' . T_CONSTANT_ENCAPSED_STRING·spec . '.', PHP_EOL;
            }
        }
        catch(\Exception $exception) {
            echo ($x ?: 'FAIL') . ': ' . T_CONSTANT_ENCAPSED_STRING·spec . '.', PHP_EOL;

            echo PHP_EOL . "\t" . $exception->getMessage() . PHP_EOL . PHP_EOL;
        }
        catch(\Error $exception) {
            echo ($x ?: 'FAIL') . ': ' . T_CONSTANT_ENCAPSED_STRING·spec . '.', PHP_EOL;

            echo PHP_EOL . "\t" . $exception->getMessage() . PHP_EOL . PHP_EOL;
        }
    })();
}
