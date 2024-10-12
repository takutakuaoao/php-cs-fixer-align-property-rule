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
    private bool $isParsingPropertySentence;

    public static function init(Tokens $tokens): self
    {
        return new self($tokens);
    }

    public function __construct(public Tokens $tokens)
    {
        $this->builder                   = TextTokenTableBuilder::init();
        $this->isParsingPropertySentence = false;
    }

    public function parse(): TextTokenTable
    {
        foreach ($this->tokens as $index => $token) {
            if (null === $token) {
                continue;
            }

            if ($this->isPropertyStartToken($index, $token)) {
                $this->addColumn($index, $token);
                continue;
            }

            if ($this->isParsingPropertySentence) {
                if (TokenUtils::isClassPropertyCandidateToken($token)) {
                    $this->addColumn($index, $token);
                    continue;
                }
            }
        }

        return $this->builder->build();
    }

    private function addColumn(int $index, Token $token): void
    {
        $this->builder->addColumn($index, $token->getContent());

        $next = $this->tokens[$index + 1];

        if (TokenUtils::isEndStatement($next)) {
            $this->endPropertyParsing();
        }
    }

    private function isPropertyStartToken(int $index, Token $token): bool
    {
        if (!TokenUtils::isPropertyStartTokenSymbol($token)) {
            return false;
        }

        $prevToken = $this->tokens[$index - 1];

        if (false === mb_strpos($prevToken->getContent(), "\n")) {
            return false;
        }

        $nextIndex = $this->tokens->getNextNonWhitespace($index);

        if (null === $nextIndex) {
            return false;
        }

        if ($this->tokens[$nextIndex]->equals([T_FUNCTION])) {
            return false;
        }

        $this->startPropertyParsing();

        return true;
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
