<?php

namespace Render\Engine\Helpers;

use RuntimeException;

class ErrorHelper
{
    private static array $messageList = [
        666 => 'Что-то явно пошло не так!',
        500 => 'Internal Server Error',
        401 => 'Unauthorized',
        400 => 'Bad Request',
        0 => 'Test',
    ];

    public static function runException(int $code, string $message = ''): void
    {
        throw new RuntimeException(self::getError($code, $message), $code);
    }

    public static function getError(int $code, string $message = ''): string
    {
        if (!empty($message)) {
            self::$messageList[$code] = $message;
        }

        return json_encode(
            [
                'message' => self::getErrorMessage($code),
                'code' => $code
            ]
        );
    }

    protected static function getErrorMessage(int $code): string
    {
        if (empty(self::$messageList[$code])) {
            self::$messageList[$code] = 'undefined';
        }

        return self::$messageList[$code];
    }
}
