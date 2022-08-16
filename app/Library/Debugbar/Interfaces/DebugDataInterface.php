<?php

namespace App\Library\Debugbar\Interfaces;

interface DebugDataInterface
{
    public function getUrl(): string;

    public function getIp(): string;

    public function getUserSession(): array;

    public function getController(): string;

    public function getControllerAction(): string;

    public function getMemoryUsage(): int;

    public function getHttpMethod(): string;

    public function getRequestData(): array;

    public function getQueryString(): string;

    public function getExecutionTimeInMicroseconds(): float;
}