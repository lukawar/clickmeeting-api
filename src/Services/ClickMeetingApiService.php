<?php

namespace lukawar\ClickmeetingApi\Services;

use lukawar\ClickmeetingApi\Consts\ClickMeetingApiConsts;
use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;

class ClickMeetingApiService
{
    protected HttpClientInterface $client;

    /**
     * Constructor
     *
     * @param HttpClientInterface $client HTTP client implementation
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new conference.
     *
     * @param array $data Conference data
     * @return array Response from API
     */
    public function createConference(array $data): array
    {
        return $this->client->post(ClickMeetingApiConsts::CONFERENCES, $data);
    }

    /**
     * Retrieve a list of all conferences.
     *
     * @return array List of conferences
     */
    public function getConferences(): array
    {
        return $this->client->get(ClickMeetingApiConsts::CONFERENCES);
    }

    /**
     * Get details of a specific conference.
     *
     * @param int $conferenceId Conference ID
     * @return array Conference details
     */
    public function getConferenceDetails(int $conferenceId): array
    {
        return $this->client->get(ClickMeetingApiConsts::CONFERENCES . "/{$conferenceId}");
    }

    /**
     * Add a user to a specific conference.
     *
     * @param int $conferenceId Conference ID
     * @param string $firstName User's first name
     * @param string $secondName User's second name
     * @param string $email User's email address
     * @return array Response from API
     */
    public function addUserToConference(int $conferenceId, string $firstName, string $secondName, string $email): array
    {
        return $this->client->post(ClickMeetingApiConsts::CONFERENCES . "/{$conferenceId}/" . ClickMeetingApiConsts::REGISTRATION, [
            ClickMeetingApiConsts::REGISTRATION => [
                1 => $firstName,
                2 => $secondName,
                3 => $email,
            ],
        ]);
    }
}