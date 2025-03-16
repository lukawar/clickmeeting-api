<?php

namespace lukawar\ClickmeetingApi\Services;

use lukawar\ClickmeetingApi\Consts\ClickMeetingApiConsts;
use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;

class ClickMeetingTokenService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Generates tokens for a given conference.
     *
     * @param int $conferenceId The ID of the conference.
     * @param int $howMany Number of tokens to generate (default is 1).
     * @return array The generated tokens.
     */
    public function generateTokens(int $conferenceId, int $howMany = 1): array
    {
        return $this->client->post(ClickMeetingApiConsts::CONFERENCES . "/{$conferenceId}/" . ClickMeetingApiConsts::TOKENS, ['how_many' => $howMany]);
    }

    /**
     * Retrieves all tokens for a given conference.
     *
     * @param int $conferenceId The ID of the conference.
     * @return array The list of tokens.
     */
    public function getConferenceTokens(int $conferenceId): array
    {
        return $this->client->get(ClickMeetingApiConsts::CONFERENCES . "/{$conferenceId}/" . ClickMeetingApiConsts::TOKENS);
    }

    /**
     * Retrieves the listener URL for a given conference.
     *
     * @param int $conferenceId The ID of the conference.
     * @param string $nickName The nickname of the listener.
     * @param string $email The email address of the listener.
     * @return array|null The listener URL or null if token generation fails.
     */
    public function getListenerUrl(int $conferenceId, string $nickName, string $email): ?array
    {
        $tokens = $this->generateTokens($conferenceId, 1);
        $token = $tokens['access_tokens'][0]['token'] ?? null;

        if (!$token) {
            return null;
        }

        return $this->client->post(ClickMeetingApiConsts::CONFERENCES . "/{$conferenceId}/" . ClickMeetingApiConsts::AUTOLOGIN, [
            'email' => $email,
            'nickname' => $nickName,
            'role' => 'listener',
            'token' => $token,
        ]);
    }
}