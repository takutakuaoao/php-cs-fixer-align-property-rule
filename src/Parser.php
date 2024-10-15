<?php

declare(strict_types=1);

namespace PhpCsFixerAlignPropertyRule;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTable;
use PhpCsFixerAlignPropertyRule\Core\TextTokenTableBuilders;

class Parser
{
    public  TextTokenTableBuilders $builder;
    private bool                   $isParsingPropertySentence;

    public static function init(Tokens $tokens): self
    {
        return new self($tokens);
    }

    public function __construct(public Tokens $tokens)
    {
        $this->builder                   = TextTokenTableBuilders::init();
        $this->isParsingPropertySentence = false;
    }

    /**
     * @return array<TextTokenTable>
     */
    public function parse(): array
    {
        foreach ($this->tokens as $index => $token) {
            if (null === $token) {
                continue;
            }

            if ($this->canStartPropertySentence($index, $token)) {
                $this->builder->addNewRow($index, $token->getContent());

                continue;
            }

            if ($this->canParseAsRestPropertySentence($token)) {
                $this->builder->addColumn($index, $token->getContent());

                $next = $this->tokens[$index + 1];

                if (TokenUtils::isEndStatement($next)) {
                    $this->endPropertyParsing();

                    if ($this->isPropertyBlockEnd($index)) {
                        $this->builder->obtainForNewPropertyBlock();
                    }
                }

                continue;
            }
        }

        if ($this->builder->isNonePropertyToken()) {
            return [];
        }

        return $this->builder->build();
    }

    private function isPropertyBlockEnd(int $index): bool
    {
        $endNextToken = $this->tokens[$index + 2];

        return mb_substr_count($endNextToken->getContent(), "\n") >= 2;
    }

    private function canStartPropertySentence(int $index, Token $token): bool
    {
        return !$this->isParsingPropertySentence && $this->isPropertyStartToken($index, $token);
    }

    private function canParseAsRestPropertySentence(Token $token): bool
    {
        return $this->isParsingPropertySentence && !$token->equals([T_WHITESPACE]);
    }

    private function isPropertyStartToken(int $index, Token $token): bool
    {
        if (!TokenUtils::isPropertyStartTokenSymbol($token)) {
            return false;
        }

        if (!$this->isStartPosition($index)) {
            return false;
        }

        if ($this->isFunctionSentence($index)) {
            return false;
        }

        $this->startPropertyParsing();

        return true;
    }

    private function isStartPosition(int $index): bool
    {
        $prevToken = $this->tokens[$index - 1];

        return TokenUtils::isBreakLine($prevToken);
    }

    private function isFunctionSentence(int $index): bool
    {
        $currentIndex = $index;
        $step         = 2;

        for ($i = 0; $i < $step; ++$i) {
            $result = $this->nextIsFunctionToken($currentIndex);

            if (null === $result) {
                return false;
            }

            if ($result['result']) {
                return true;
            }

            $currentIndex = $result['nextIndex'];
        }


        return false;
    }

    /**
     * @return array{result: bool, nextIndex: int}|null
     */
    private function nextIsFunctionToken(int $index): ?array
    {
        $nextIndex = $this->tokens->getNextNonWhitespace($index);

        if (null === $nextIndex) {
            return null;
        }

        if ($this->tokens[$nextIndex]->equals([T_FUNCTION])) {
            return ['result' => true, 'nextIndex' => $nextIndex];
        }

        return ['result' => false, 'nextIndex' => $nextIndex];
    }

    private function startPropertyParsing(): void
    {
        $this->isParsingPropertySentence = true;
    }

    private function endPropertyParsing(): void
    {
        $this->isParsingPropertySentence = false;
    }
}
