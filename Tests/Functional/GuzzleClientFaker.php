<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class GuzzleClientFaker
{
    private static ?MockHandler $mockHandler = null;

    public static function registerClient(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['HTTP']['handler']['faker'] = function (callable $handler) {
            return self::getMockHandler();
        };

    }

    /**
     * Cleans things up, call it in tests tearDown() method.
     */
    public static function tearDown(): void
    {
        unset($GLOBALS['TYPO3_CONF_VARS']['HTTP']['handler']['faker']);
    }

    /**
     * Adds a new response to the stack with defaults, returning the file contents of given file.
     */
    public static function appendResponseFromFile(string $fileName): void
    {
        $fileContent = file_get_contents($fileName);
        if ($fileContent === false) {
            throw new \Exception('Could not load file: ' . $fileName, 1656485162);
        }

        self::appendResponseFromContent($fileContent);
    }

    public static function appendResponseFromContent(string $content): void
    {
        self::appendResponse(new Response(
            SymfonyResponse::HTTP_OK,
            [],
            $content
        ));
    }

    private static function getMockHandler(): MockHandler
    {
        if (!self::$mockHandler instanceof MockHandler) {
            self::$mockHandler = new MockHandler();
        }

        return self::$mockHandler;
    }

    private static function appendResponse(Response $response): void
    {
        self::getMockHandler()->append($response);
    }
}
