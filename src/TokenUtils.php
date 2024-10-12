<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\Tokenizer\Token;

class TokenUtils
{
    private const CLASS_PROPERTY_START_CANDIDATE_SYMBOLS = [
        [T_PRIVATE, 'private'],
        [T_PROTECTED, 'protected'],
        [T_PUBLIC, 'public'],
        [T_READONLY, 'readonly'],
        [T_STATIC, 'static'],
        [T_CONST, 'const'],
    ];

    public static function isPropertyStartTokenSymbol(Token $token): bool
    {
        return self::orTokenEquals(
            $token,
            self::CLASS_PROPERTY_START_CANDIDATE_SYMBOLS,
        );
    }

    public static function isEndStatement(Token $token): bool
    {
        return ';' === $token->getContent();
    }

    /**
     * @param array<array{0: int, 1?: string}|string> $conditions
     */
    private static function orTokenEquals(Token $token, array $conditions): bool
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
}
