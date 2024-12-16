<?php

declare(strict_types=1);

namespace App\Helpers;

use PHPUnit\Framework\TestCase;

abstract class ServerTestManager extends TestCase {

    protected static $serverProcess;

    public static function setUpBeforeClass(): void {
        echo "\n\n * Starting the server...\n\n";
        self::$serverProcess = proc_open("composer run start:test", [], $pipes);
        sleep(4);
    }

    public static function tearDownAfterClass(): void {
        echo "\n\n * Stopping the server...\n\n";
        if(self::$serverProcess){
            proc_terminate(self::$serverProcess);
        }
    }

}
