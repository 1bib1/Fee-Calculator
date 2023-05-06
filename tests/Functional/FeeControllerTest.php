<?php

declare(strict_types=1);

namespace App\FeeCalculator\Tests\Functional;

use App\FeeCalculator\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeeControllerTest extends ApiTestCase
{
    private const URL = '/api/fee/calculate';

    public function testCanSendRequestGivenValuesAreValid(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            self::URL,
            content: json_encode([
                'term' => 12,
                'amount' => 1000,
        ]));

        $this->assertResponseIsSuccessful();
    }

    public function testValidationFailsWhenInvalidValuesAreSent(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            self::URL,
            content: json_encode([
                'term' => 999,
                'amount' => 888,
            ]));

        self::assertSame(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->client->getResponse()->getStatusCode(),
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('errors', $response);
        self::assertCount(2, $response['errors']);
        self::assertArrayHasKey('term', $response['errors']);
        self::assertSame('The value you selected is not a valid choice.', $response['errors']['term']['message']);
        self::assertArrayHasKey('amount', $response['errors']);
        self::assertSame('This value should be between 1000 and 20000.', $response['errors']['amount']['message']);
    }

    public function testCannotMakeRequestWithoutValues(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            self::URL,
            content: json_encode([])
        );

        self::assertSame(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->client->getResponse()->getStatusCode(),
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('errors', $response);
        self::assertCount(2, $response['errors']);
        self::assertArrayHasKey('term', $response['errors']);
        self::assertArrayHasKey('amount', $response['errors']);
        self::assertSame('This value should not be blank.', $response['errors']['term']['message']);
        self::assertSame('This value should not be blank.', $response['errors']['amount']['message']);
    }
}
