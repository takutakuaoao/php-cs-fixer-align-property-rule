<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\Tokenizer\Token;

class TokenUtils
{
    public const CONST_KEY_WORD_RULE = [T_CONST, 'const'];

    public static function isConstValueSymbol(Token $token): bool
    {
        return self::orTokenEquals($token, [
            [T_CONSTANT_ENCAPSED_STRING],
            [T_LNUMBER],
            [T_STRING, 'true'],
            [T_STRING, 'false'],
        ]);
    }

    /**
     * @param array<array{0: int, 1?: string}|string> $conditions
     */
    public static function orTokenEquals(Token $token, array $conditions): bool
    {
        if ([] === $conditions) {
            return false;
        }

        $currentCondition = array_shift($conditions);

        if ($token->equals($currentCondition)) {
            return true;
        }

        return self::orTokenEquals($token, $conditions);
    }

    public static function isPropertyStartTokenSymbol(Token $token): bool
    {
        return self::isAccessiblyTokenSymbol($token)
            || self::orTokenEquals($token, [
                [T_READONLY, 'readonly'],
                [T_STATIC, 'static'],
            ]);
    }

    public static function isAccessiblyTokenSymbol(Token $token): bool
    {
        return self::orTokenEquals($token, [
            [T_PRIVATE, 'private'],
            [T_PROTECTED, 'protected'],
            [T_PUBLIC, 'public'],
        ]);
    }
}
