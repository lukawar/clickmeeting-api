<?php

namespace lukawar\ClickmeetingApi\Tests;

use lukawar\ClickmeetingApi\Clients\ClickMeetingApiClient;
use lukawar\ClickmeetingApi\Services\ClickMeetingApiService;
use lukawar\ClickmeetingApi\Contracts\HttpClientInterface;
use PHPUnit\Framework\TestCase;

class ClickMeetingServiceTest extends TestCase
{
    protected ClickMeetingApiService $service;
    protected $httpClientMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->service = new ClickMeetingApiService($this->httpClientMock);
    }

    public function testAddUserToConference(): void
    {
        $conferenceId = 123;
        $firstName = 'John';
        $secondName = 'Doe';
        $email = 'john@example.com';

        $expectedResponse = ['success' => true];

        $this->httpClientMock
            ->expects($this->once())
            ->method('post')
            ->with("conferences/{$conferenceId}/registration", [
                'registration' => [1 => $firstName, 2 => $secondName, 3 => $email]
            ])
            ->willReturn($expectedResponse);

        $response = $this->service->addUserToConference($conferenceId, $firstName, $secondName, $email);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetConferences(): void
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'Conference 1'],
            ['id' => 2, 'name' => 'Conference 2'],
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('get')
            ->with('conferences')
            ->willReturn($expectedResponse);

        $response = $this->service->getConferences();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGenerateTokens(): void
    {
        $conferenceId = 456;
        $howMany = 5;

        $expectedResponse = [
            'access_tokens' => [['token' => 'ABC123'], ['token' => 'DEF456']]
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('post')
            ->with("conferences/{$conferenceId}/tokens", ['how_many' => $howMany])
            ->willReturn($expectedResponse);

        $response = $this->service->generateTokens($conferenceId, $howMany);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetConferenceDetails(): void
    {
        $conferenceId = 789;

        $expectedResponse = [
            'id' => $conferenceId,
            'name' => 'Sample Conference'
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('get')
            ->with("conferences/{$conferenceId}")
            ->willReturn($expectedResponse);

        $response = $this->service->getConferenceDetails($conferenceId);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetListenerUrl(): void
    {
        $conferenceId = 101;
        $nickName = 'JaneDoe';
        $email = 'jane@example.com';

        $tokenResponse = [
            'access_tokens' => [['token' => 'XYZ789']]
        ];

        $expectedResponse = [
            'url' => 'https://example.com/room/join'
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('post')
            ->with("conferences/{$conferenceId}/tokens", ['how_many' => 1])
            ->willReturn($tokenResponse);

        $this->httpClientMock
            ->expects($this->once())
            ->method('post')
            ->with("conferences/{$conferenceId}/room/autologin_hash", [
                'email' => $email,
                'nickname' => $nickName,
                'role' => 'listener',
                'token' => 'XYZ789',
            ])
            ->willReturn($expectedResponse);

        $response = $this->service->getListenerUrl($conferenceId, $nickName, $email);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetConferenceTokens(): void
    {
        $conferenceId = 202;

        $expectedResponse = [
            ['token' => 'TOKEN123'],
            ['token' => 'TOKEN456']
        ];

        $this->httpClientMock
            ->expects($this->once())
            ->method('get')
            ->with("conferences/{$conferenceId}/tokens")
            ->willReturn($expectedResponse);

        $response = $this->service->getConferenceTokens($conferenceId);

        $this->assertEquals($expectedResponse, $response);
    }
}