<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Hyperf\Seata\SqlParser\Antlr\MySql\Parser\Context;

use Antlr\Antlr4\Runtime\ParserRuleContext;
    use Antlr\Antlr4\Runtime\Token;
    use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
    use Antlr\Antlr4\Runtime\Tree\TerminalNode;
    use Hyperf\Seata\SqlParser\Antlr\MySql\Listener\MySqlParserListener;
    use Hyperf\Seata\SqlParser\Antlr\MySql\Parser\MySqlParser;

    class ConvertedDataTypeContext extends ParserRuleContext
    {
        /**
         * @var null|Token
         */
        public $typeName;

        public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
        {
            parent::__construct($parent, $invokingState);
        }

        public function getRuleIndex(): int
        {
            return MySqlParser::RULE_convertedDataType;
        }

        public function CHAR(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::CHAR, 0);
        }

        public function DECIMAL(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::DECIMAL, 0);
        }

        public function SIGNED(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::SIGNED, 0);
        }

        public function UNSIGNED(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::UNSIGNED, 0);
        }

        public function ARRAY(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::ARRAY, 0);
        }

        public function BINARY(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::BINARY, 0);
        }

        public function NCHAR(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::NCHAR, 0);
        }

        public function DATE(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::DATE, 0);
        }

        public function DATETIME(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::DATETIME, 0);
        }

        public function TIME(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::TIME, 0);
        }

        public function JSON(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::JSON, 0);
        }

        public function INT(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::INT, 0);
        }

        public function INTEGER(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::INTEGER, 0);
        }

        public function lengthOneDimension(): ?LengthOneDimensionContext
        {
            return $this->getTypedRuleContext(LengthOneDimensionContext::class, 0);
        }

        public function charsetName(): ?CharsetNameContext
        {
            return $this->getTypedRuleContext(CharsetNameContext::class, 0);
        }

        public function lengthTwoOptionalDimension(): ?LengthTwoOptionalDimensionContext
        {
            return $this->getTypedRuleContext(LengthTwoOptionalDimensionContext::class, 0);
        }

        public function CHARACTER(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::CHARACTER, 0);
        }

        public function SET(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::SET, 0);
        }

        public function CHARSET(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::CHARSET, 0);
        }

        public function enterRule(ParseTreeListener $listener): void
        {
            if ($listener instanceof MySqlParserListener) {
                $listener->enterConvertedDataType($this);
            }
        }

        public function exitRule(ParseTreeListener $listener): void
        {
            if ($listener instanceof MySqlParserListener) {
                $listener->exitConvertedDataType($this);
            }
        }
    }
