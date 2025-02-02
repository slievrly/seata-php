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
namespace Hyperf\Seata\Core\Codec\Seata\Protocol\Transaction;

use Hyperf\Seata\Core\Codec\Seata\Protocol\AbstractResultMessageCodec;
use Hyperf\Seata\Core\Protocol\AbstractMessage;
use Hyperf\Seata\Core\Protocol\Transaction\AbstractTransactionResponse;
use Hyperf\Seata\Utils\Buffer\ByteBuffer;

abstract class AbstractTransactionResponseCodec extends AbstractResultMessageCodec
{
    public function getMessageClassType(): string
    {
        return AbstractTransactionResponse::class;
    }

    public function encode(AbstractMessage $message, ByteBuffer $buffer): ByteBuffer
    {
        parent::encode($message, $buffer);

        if (! $message instanceof AbstractTransactionResponse) {
            throw new \InvalidArgumentException('Invalid message');
        }
        $buffer->putUByte($message->getTransactionExceptionCode());
        return $buffer;
    }

    public function decode(AbstractMessage $message, ByteBuffer $buffer): AbstractMessage
    {
        parent::decode($message, $buffer);

        if (! $message instanceof AbstractTransactionResponse) {
            throw new \InvalidArgumentException('Invalid message');
        }

        $message->setTransactionExceptionCode((int) $buffer->readUByte());
        return $message;
    }
}
