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
namespace Hyperf\Seata\Tm\Api\Transaction;

/**
 * Propagation level of global transactions.
 *
 * @todo 修改对应的注释
 * @see io.seata.spring.annotation.GlobalTransactional#propagation() // TM annotation
 * @see io.seata.spring.annotation.GlobalTransactionalInterceptor#invoke(MethodInvocation) // the interceptor of TM
 * @see io.seata.tm.api.TransactionalTemplate#execute(TransactionalExecutor) // the transaction template of TM
 */
class Propagation
{
    /**
     * The REQUIRED.
     * The default propagation.
     *
     * <p>
     * If transaction is existing, execute with current transaction,
     * else execute with new transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     if (tx == null) {
     *         try {
     *             tx = beginNewTransaction(); // begin new transaction, is not existing
     *             Object rs = business.execute(); // execute with new transaction
     *             commitTransaction(tx);
     *             return rs;
     *         } catch (Exception ex) {
     *             rollbackTransaction(tx);
     *             throw ex;
     *         }
     *     } else {
     *         return business.execute(); // execute with current transaction
     *     }
     * </pre></code>
     * </p>
     */
    public const REQUIRED = 0;

    /**
     * The REQUIRES_NEW.
     *
     * <p>
     * If transaction is existing, suspend it, and then execute business with new transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     try {
     *         if (tx != null) {
     *             suspendedResource = suspendTransaction(tx); // suspend current transaction
     *         }
     *         try {
     *             tx = beginNewTransaction(); // begin new transaction
     *             Object rs = business.execute(); // execute with new transaction
     *             commitTransaction(tx);
     *             return rs;
     *         } catch (Exception ex) {
     *             rollbackTransaction(tx);
     *             throw ex;
     *         }
     *     } finally {
     *         if (suspendedResource != null) {
     *             resumeTransaction(suspendedResource); // resume transaction
     *         }
     *     }
     * </pre></code>
     * </p>
     */
    public const REQUIRES_NEW = 1;

    /**
     * The NOT_SUPPORTED.
     *
     * <p>
     * If transaction is existing, suspend it, and then execute business without transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     try {
     *         if (tx != null) {
     *             suspendedResource = suspendTransaction(tx); // suspend current transaction
     *         }
     *         return business.execute(); // execute without transaction
     *     } finally {
     *         if (suspendedResource != null) {
     *             resumeTransaction(suspendedResource); // resume transaction
     *         }
     *     }
     * </pre></code>
     * </p>
     */
    public const NOT_SUPPORTED = 2;

    /**
     * The SUPPORTS.
     *
     * <p>
     * If transaction is not existing, execute without global transaction,
     * else execute business with current transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     if (tx != null) {
     *         return business.execute(); // execute with current transaction
     *     } else {
     *         return business.execute(); // execute without transaction
     *     }
     * </pre></code>
     * </p>
     */
    public const SUPPORTS = 3;

    /**
     * The NEVER.
     *
     * <p>
     * If transaction is existing, throw exception,
     * else execute business without transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     if (tx != null) {
     *         throw new TransactionException("existing transaction");
     *     }
     *     return business.execute(); // execute without transaction
     * </pre></code>
     * </p>
     */
    public const NEVER = 4;

    /**
     * The MANDATORY.
     *
     * <p>
     * If transaction is not existing, throw exception,
     * else execute business with current transaction.
     * </p>
     *
     * <p>
     * The logic is similar to the following code:
     * <code><pre>
     *     if (tx == null) {
     *         throw new TransactionException("not existing transaction");
     *     }
     *     return business.execute(); // execute with current transaction
     * </pre></code>
     * </p>
     */
    public const MANDATORY = 5;

    /**
     * @var int
     */
    private $propagation;

    public function __construct($propagation)
    {
        $this->propagation = $propagation;
    }

    public function getPropagation(): int
    {
        return $this->propagation;
    }

    public function setPropagation(int $propagation): void
    {
        $this->propagation = $propagation;
    }
}
