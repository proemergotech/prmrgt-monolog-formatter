<?php

namespace ProEmergotech\Logger\Formatter\Tests;

use DateTime;
use Faker\Factory as FakerFactory;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use ProEmergotech\Logger\Formatter\PrmrgtLogFormatter;

class PrmrgtLogFormatterTest extends TestCase
{
    private const KEY_VALUES_TO_EXTRACT = [
        'correlation_id',
        'test'
    ];

    /**
     * Tests proper formatting capability.
     *
     * Scheme: time severity message ##<keyvalues>##payload
     *
     * @param array $logRecord
     *
     * @dataProvider logRecordProvider
     */
    public function testCanFormatLogEntry(array $logRecord)
    {
        $regexPattern = include 'regexPatterns.php';

        $logFormatter = new PrmrgtLogFormatter(self::KEY_VALUES_TO_EXTRACT);

        $formattedLogEntry = $logFormatter->format($logRecord);

        // Checks for proper formatting of log entry.
        $matchedParts = [];
        preg_match(
            '/' . $regexPattern['iso8601'] . ' ' . $regexPattern['logLevel'] . ' (.*?) ##<(.*?)>##(.*)' . PHP_EOL  . '/',
            $formattedLogEntry,
            $matchedParts
        );

        // Checks if formatter extracts key-values as needed or not.
        foreach (self::KEY_VALUES_TO_EXTRACT as $keyValueToExtract)
        {
            if (!array_key_exists($keyValueToExtract, $logRecord['context']))
            {
                continue;
            }

            $this->assertFalse(strpos($matchedParts[4], $keyValueToExtract . '=') === false);
        }

        $this->assertTrue($matchedParts[0] === $formattedLogEntry);
    }

    public function logRecordProvider()
    {
        $faker = FakerFactory::create();

        return [
            [
                [
                    'message'    => 'test message 1',
                    'context'    => [],
                    'level'      => Logger::DEBUG,
                    'level_name' => Logger::getLevelName(Logger::DEBUG),
                    'channel'    => 'log-formatter',
                    'datetime'   => new DateTime(),
                    'extra'      => []
                ]
            ],
            [
                [
                    'message'    => 'test message 3',
                    'context'    => [
                        self::KEY_VALUES_TO_EXTRACT[0] => $faker->uuid,
                        self::KEY_VALUES_TO_EXTRACT[1] => 'test'
                    ],
                    'level'      => Logger::INFO,
                    'level_name' => Logger::getLevelName(Logger::INFO),
                    'channel'    => 'log-formatter',
                    'datetime'   => new DateTime(),
                    'extra'      => []
                ]
            ],
            [
                [
                    'message'    => 'test message 2',
                    'context'    => [self::KEY_VALUES_TO_EXTRACT[0] => $faker->uuid],
                    'level'      => Logger::INFO,
                    'level_name' => Logger::getLevelName(Logger::INFO),
                    'channel'    => 'log-formatter',
                    'datetime'   => new DateTime(),
                    'extra'      => []
                ]
            ]
        ];
    }
}
