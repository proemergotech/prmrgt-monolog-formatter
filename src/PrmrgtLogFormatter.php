<?php

namespace ProEmergotech\Logger\Formatter;

use Monolog\Formatter\NormalizerFormatter;

class PrmrgtLogFormatter extends NormalizerFormatter
{
    /** RFC3339/ISO8601 datetime with microseconds. */
    private const DEFAULT_LOG_DATETIME_FORMAT = 'Y-m-d\TH:i:s.uP';

    /** @var array */
    private $extractAsKeyValues;

    public function __construct(array $extractAsKeyValues = [], string $dateFormat = null)
    {
        $this->extractAsKeyValues = $extractAsKeyValues;

        parent::__construct($dateFormat ?? self::DEFAULT_LOG_DATETIME_FORMAT);
    }

    public function format(array $record): string
    {
        $record = $this->normalize($record);

        $logMessageOutput = $record['datetime'] . ' ' .  $record['level_name'] . ' ' . $record['message'] . ' ##<';
        foreach ($this->extractAsKeyValues as $extractAsKeyValue)
        {
            if (isset($record['context'][$extractAsKeyValue])) {
                $extractedValue = $record['context'][$extractAsKeyValue];
                $logMessageOutput .= $extractAsKeyValue . '=' . $extractedValue . ';';
            }
        }
        $logMessageOutput .= '>##' . $this->toJson($record['context'], true);

        return $logMessageOutput;
    }
}
