<?php

namespace lukawar\ClickmeetingApi\Services;

use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;

class ClickMeetingApiService
{
    protected HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function createConference(array $data): array
    {
        return $this->client->post('conferences', $data);
    }

    public function getConferences(): array
    {
        return $this->client->get('conferences');
    }

    public function getConferenceDetails(int $conferenceId): array
    {
        return $this->client->get("conferences/{$conferenceId}");
    }

    public function addUserToConference(int $conferenceId, string $firstName, string $secondName, string $email): array
    {
        return $this->client->post("conferences/{$conferenceId}/registration", [
            'registration' => [
                1 => $firstName,
                2 => $secondName,
                3 => $email,
            ],
        ]);
    }

    public function generateTokens(int $conferenceId, int $howMany = 1): array
    {
        return $this->client->post("conferences/{$conferenceId}/tokens", ['how_many' => $howMany]);
    }

    public function getConferenceTokens(int $conferenceId): array
    {
        return $this->client->get("conferences/{$conferenceId}/tokens");
    }

    public function getListenerUrl(int $conferenceId, string $nickName, string $email): ?array
    {
        $tokens = $this->generateTokens($conferenceId, 1);
        $token = $tokens['access_tokens'][0]['token'] ?? null;

        if (!$token) {
            return null;
        }

        return $this->client->post("conferences/{$conferenceId}/room/autologin_hash", [
            'email' => $email,
            'nickname' => $nickName,
            'role' => 'listener',
            'token' => $token,
        ]);
    }
}