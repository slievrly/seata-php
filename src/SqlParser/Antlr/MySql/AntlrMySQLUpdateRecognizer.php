<?php

namespace Hyperf\Seata\SqlParser\Antlr\MySql;

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Tree\ParseTreeWalker;
use Hyperf\Seata\SqlParser\Antlr\MySql\Listener\UpdateSpecificationSqlListener;
use Hyperf\Seata\SqlParser\Antlr\MySql\Parser\MySqlLexer;
use Hyperf\Seata\SqlParser\Antlr\MySql\Stream\ANTLRNoCaseStringStream;
use Hyperf\Seata\SqlParser\Antlr\MySqlContext;
use Hyperf\Seata\SqlParser\Core\ParametersHolder;
use Hyperf\Seata\SqlParser\Core\SQLType;
use Hyperf\Seata\SqlParser\Core\SQLUpdateRecognizer;
use Hyperf\Seata\SqlParser\Antlr\MySql\Parser\MySqlParser;

class AntlrMySQLUpdateRecognizer implements SQLUpdateRecognizer
{

    private MySqlContext $sqlContext;


    public function __construct(string $sql)
    {
        $mySqlLexer = new MySqlLexer(new ANTLRNoCaseStringStream($sql));

        $commonTokenStream = new CommonTokenStream($mySqlLexer);

        $parser2 = new MySqlParser($commonTokenStream);

        $root = $parser2->root();

        $walker2 = new ParseTreeWalker();

        $this->sqlContext = new MySqlContext();
        $this->sqlContext->setOriginalSQL($sql);
        $walker2->walk(new UpdateSpecificationSqlListener($this->sqlContext), $root);

    }

    public function getSQLType(): SQLType
    {
        return new SQLType(SQLType::UPDATE);
    }

    public function getTableAlias(): string
    {
        return $this->sqlContext->getTableAlias();
    }

    public function getTableName(): string
    {
        return $this->sqlContext->getTableName();
    }

    public function getOriginalSQL(): string
    {
        return $this->sqlContext->getOriginalSQL();
    }

    public function getUpdateColumns(): array
    {
        return $this->sqlContext->getUpdateFoColumnNames();
    }

    public function getUpdateValues(): array
    {
        $updateForValues = $this->sqlContext->getUpdateForValues();
        if (empty($updateForValues)) {
            return [];
        }

        return $updateForValues;
    }

    public function getWhereConditionWithParametersHolderAndList(ParametersHolder $parametersHolder, array $paramAppenderList): string
    {
        return  $this->sqlContext->getWhereCondition();
    }

    public function getWhereCondition(): string
    {
       return  $this->sqlContext->getWhereCondition();
    }

    public function getLimit(ParametersHolder $parametersHolder, array $paramAppenderList): string
    {
        // TODO: Implement getLimit() method.
    }

    public function getOrderBy(): string
    {
        // TODO: Implement getOrderBy() method.
    }
}