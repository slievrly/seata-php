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

    use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
    use Antlr\Antlr4\Runtime\Tree\TerminalNode;
    use Hyperf\Seata\SqlParser\Antlr\MySql\Listener\MySqlParserListener;
    use Hyperf\Seata\SqlParser\Antlr\MySql\Parser\MySqlParser;

    class JsonValueFunctionCallContext extends SpecificFunctionContext
    {
        public function __construct(SpecificFunctionContext $context)
        {
            parent::__construct($context);

            $this->copyFrom($context);
        }

        public function JSON_VALUE(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::JSON_VALUE, 0);
        }

        public function LR_BRACKET(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::LR_BRACKET, 0);
        }

        /**
         * @return null|array<ExpressionContext>|ExpressionContext
         */
        public function expression(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(ExpressionContext::class);
            }

            return $this->getTypedRuleContext(ExpressionContext::class, $index);
        }

        public function COMMA(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::COMMA, 0);
        }

        public function RR_BRACKET(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::RR_BRACKET, 0);
        }

        public function RETURNING(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::RETURNING, 0);
        }

        public function convertedDataType(): ?ConvertedDataTypeContext
        {
            return $this->getTypedRuleContext(ConvertedDataTypeContext::class, 0);
        }

        /**
         * @return null|array<TerminalNode>|TerminalNode
         */
        public function ON(?int $index = null)
        {
            if ($index === null) {
                return $this->getTokens(MySqlParser::ON);
            }

            return $this->getToken(MySqlParser::ON, $index);
        }

        public function EMPTY(): ?TerminalNode
        {
            return $this->getToken(MySqlParser::EMPTY, 0);
        }

        /**
         * @return null|array<TerminalNode>|TerminalNode
         */
        public function ERROR(?int $index = null)
        {
            if ($index === null) {
                return $this->getTokens(MySqlParser::ERROR);
            }

            return $this->getToken(MySqlParser::ERROR, $index);
        }

        /**
         * @return null|array<TerminalNode>|TerminalNode
         */
        public function NULL_LITERAL(?int $index = null)
        {
            if ($index === null) {
                return $this->getTokens(MySqlParser::NULL_LITERAL);
            }

            return $this->getToken(MySqlParser::NULL_LITERAL, $index);
        }

        /**
         * @return null|array<TerminalNode>|TerminalNode
         */
        public function DEFAULT(?int $index = null)
        {
            if ($index === null) {
                return $this->getTokens(MySqlParser::DEFAULT);
            }

            return $this->getToken(MySqlParser::DEFAULT, $index);
        }

        /**
         * @return null|array<DefaultValueContext>|DefaultValueContext
         */
        public function defaultValue(?int $index = null)
        {
            if ($index === null) {
                return $this->getTypedRuleContexts(DefaultValueContext::class);
            }

            return $this->getTypedRuleContext(DefaultValueContext::class, $index);
        }

        public function enterRule(ParseTreeListener $listener): void
        {
            if ($listener instanceof MySqlParserListener) {
                $listener->enterJsonValueFunctionCall($this);
            }
        }

        public function exitRule(ParseTreeListener $listener): void
        {
            if ($listener instanceof MySqlParserListener) {
                $listener->exitJsonValueFunctionCall($this);
            }
        }
    }
