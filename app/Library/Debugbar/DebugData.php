<?php

namespace App\Library\Debugbar;

use App\Library\Debugbar\Interfaces\DebugDataInterface;

class DebugData implements DebugDataInterface
{
    private string $url;
    private string $ip;
    private array $userSession;
    private string $controller;
    private string $controllerAction;
    private int $memoryUsage;
    private string $httpMethod;
    private array $requestData;
    private string $queryString;
    private float $executionTime;

    public function __construct(string $url, string $ip, array $userSession, string $controller, string $controllerAction, int $memoryUsage, string $httpMethod, array $requestData, string $queryString, float $executionTime)
    {
        $this->url = $url;
        $this->ip = $ip;
        $this->userSession = $userSession;
        $this->controller = $controller;
        $this->controllerAction = $controllerAction;
        $this->memoryUsage = $memoryUsage;
        $this->httpMethod = $httpMethod;
        $this->requestData = $requestData;
        $this->queryString = $queryString;
        $this->executionTime = $executionTime;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getUserSession(): array
    {
        return $this->userSession;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getControllerAction(): string
    {
        return $this->controllerAction;
    }

    public function getMemoryUsage(): int
    {
        return $this->memoryUsage;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    public function getExecutionTimeInMicroseconds(): float
    {
        return $this->executionTime;
    }
}