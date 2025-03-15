<?php

namespace lukawar\ClickmeetingApi\Contracts;

interface HttpClientInterface
{
    public function get(string $endpoint): array;
    public function post(string $endpoint, array $data): array;
}