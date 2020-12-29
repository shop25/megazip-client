<?php

namespace S25\MegazipApiClient;

use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;

class Options
{
    private const TRACE = <<<FORMAT
>>>>>>>>
{req_headers}
{req_body}
<<<<<<<<
{res_headers}
{res_body}
--------
{error}

FORMAT;

    public ?LoggerInterface $logger = null;
    public bool $trace = false;
    public string $format = MessageFormatter::CLF . "\n";
    public string $traceFormat = MessageFormatter::CLF . "\n" . self::TRACE;

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function setTrace(bool $trace): self
    {
        $this->trace = $trace;

        return $this;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function setTraceFormat(string $traceFormat): self
    {
        $this->traceFormat = $traceFormat;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->trace ? $this->traceFormat : $this->format;
    }

    public static function new(): self
    {
        return new self();
    }
}