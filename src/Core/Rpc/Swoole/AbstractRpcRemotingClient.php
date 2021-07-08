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
namespace Hyperf\Seata\Core\Rpc\Swoole;

use Hyperf\Seata\Core\Protocol\AbstractMessage;
use Hyperf\Seata\Core\Protocol\Transaction\GlobalBeginResponse;
use Hyperf\Seata\Core\Rpc\AbstractRpcRemoting;
use Hyperf\Seata\Core\Rpc\Address;
use Hyperf\Seata\Discovery\Registry\RegistryFactory;
use Hyperf\Seata\Exception\SeataErrorCode;
use Hyperf\Seata\Exception\SeataException;
use Hyperf\Utils\ApplicationContext;

abstract class AbstractRpcRemotingClient extends AbstractRpcRemoting
{
    protected const MSG_ID_PREFIX = 'msgId:';

    protected const FUTURES_PREFIX = 'futures:';

    protected const SINGLE_LOG_POSTFIX = ';';

    protected const MAX_MERGE_SEND_MILLS = 1;

    protected const THREAD_PREFIX_SPLIT_CHAR = '_';

    protected const MAX_MERGE_SEND_THREAD = 1;

    protected const KEEP_ALIVE_TIME = PHP_INT_MAX;

    protected const SCHEDULE_INTERVAL_MILLS = 5;

    protected const MERGE_THREAD_PREFIX = 'rpcMergeMessageSend';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var RegistryFactory
     */
    protected $registryFactory;

    /**
     * @var \Hyperf\Seata\Core\Rpc\Swoole\SocketManager
     */
    protected $socketManager;

    /**
     * @var int
     * @see \Hyperf\Seata\Core\Rpc\TransactionRole
     */
    protected $transactionRole;

    public function __construct(int $transactionRole)
    {
        parent::__construct();
        $this->transactionRole = $transactionRole;
        $container = ApplicationContext::getContainer();
        $this->registryFactory = $container->get(RegistryFactory::class);
        $this->socketManager = $container->get(SocketManager::class);
    }

    public function init()
    {
        // @TODO 启动一个 reconnect 的 Timer
    }

    /**
     * @return \Hyperf\Seata\Core\Protocol\Transaction\GlobalBeginResponse
     */
    public function sendMsgWithResponse(AbstractMessage $message, int $timeout = 100)
    {
        $validAddress = $this->loadBalance($this->getTransactionServiceGroup());
        $channel = $this->socketManager->acquireChannel($validAddress);
        $result = $this->sendAsyncRequestWithResponse($channel, $message, $timeout);
        if ($result instanceof GlobalBeginResponse && ! $result->getResultCode()) {
            if ($this->logger) {
                $this->logger->error('begin response error,release socket');
            }
        }
        return $result;
    }

    abstract protected function getTransactionServiceGroup(): string;

    private function loadBalance(string $transactionServiceGroup): ?Address
    {
        $address = null;
        try {
            $addressList = $this->registryFactory->getInstance()->lookup($transactionServiceGroup);
            // @todo 通过负载均衡器选择一个地址
            $address = $addressList[0];
        } catch (\Exception $exception) {
            if ($this->logger) {
                $this->logger->error($exception->getMessage());
            }
        }
        if (! $address instanceof Address) {
            throw new SeataException(SeataErrorCode::NoAvailableService);
        }
        return $address;
    }
}
