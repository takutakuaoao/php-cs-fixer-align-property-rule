<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTable;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTableBuilder;

class Parser
{
    private TextTokenTableBuilder $builder;

    public static function init(Tokens $tokens): self
    {
        return new self($tokens);
    }

    public function __construct(public Tokens $tokens)
    {
        $this->builder = TextTokenTableBuilder::init();
    }

    public function parse(): TextTokenTable
    {
        foreach ($this->tokens as $index => $token) {
            if (null === $token) {
                continue;
            }

            if (
                $this->isConstStatementToken($index, $token)
                || $this->isPropertyStartToken($index, $token)
                || $this->isPropertyVariableToken($index, $token)
            ) {
                $this->builder->addColumn($index, $token->getContent());
            }
        }

        return $this->builder->build();
    }

    private function isConstStatementToken(int $index, Token $token): bool
    {
        if (TokenUtils::isConstValueSymbol($token)) {
            return $this->isPrevTokenValidate($index, ['=', [T_STRING], TokenUtils::CONST_KEY_WORD_RULE]);
        }

        if ($token->equals(TokenUtils::CONST_KEY_WORD_RULE)) {
            return true;
        }

        if ('=' === $token->getContent()) {
            return $this->isPrevTokenValidate($index, [[T_STRING], TokenUtils::CONST_KEY_WORD_RULE]);
        }

        if ($token->equals([T_STRING])) {
            return $this->isPrevTokenValidate($index, [TokenUtils::CONST_KEY_WORD_RULE]);
        }

        return false;
    }

    /**
     * @param array<array{0: int, 1?: string}|string> $conditions
     */
    private function isPrevTokenValidate(int $index, array $conditions): bool
    {
        if ([] === $conditions) {
            return true;
        }

        $currentCondition = array_shift($conditions);

        $prev = $this->tokens->getPrevNonWhitespace($index);

        if ($prev && $this->tokens[$prev]->equals($currentCondition)) {
            return $this->isPrevTokenValidate($prev, $conditions);
        }

        return false;
    }

    private function isPropertyStartToken(int $index, Token $token): bool
    {
        if (!TokenUtils::isPropertyStartTokenSymbol($token)) {
            return false;
        }

        $nextTokenIndex = $this->tokens->getNextNonWhitespace($index);

        if (null === $nextTokenIndex || !$this->tokens[$nextTokenIndex]->equals([T_VARIABLE])) {
            return false;
        }

        return true;
    }

    private function isPropertyVariableToken(int $index, Token $token): bool
    {
        if ($token->equals([T_VARIABLE])) {
            $prevIndex = $this->tokens->getPrevNonWhitespace($index);

            if ($prevIndex && $this->isPropertyStartToken($prevIndex, $this->tokens[$prevIndex])) {
                return true;
            }
        }

        return false;
    }
}
