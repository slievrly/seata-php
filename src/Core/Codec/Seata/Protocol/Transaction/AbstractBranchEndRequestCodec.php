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

use Hyperf\Seata\Core\Protocol\AbstractMessage;
use Hyperf\Seata\Core\Protocol\Codec\Strings;
use Hyperf\Seata\Core\Protocol\Transaction\AbstractBranchEndRequest;
use Hyperf\Seata\Utils\Buffer\ByteBuffer;

abstract class AbstractBranchEndRequestCodec extends AbstractTransactionRequestToRMCodec
{
    public function getMessageClassType(): string
    {
        return AbstractBranchEndRequest::class;
    }

    public function encode(AbstractMessage $message, ByteBuffer $buffer): ByteBuffer
    {
        if (! $message instanceof AbstractBranchEndRequest) {
            throw new \InvalidArgumentException('Invalid message');
        }

        // Xid
        if ($message->getXid() !== null) {
            $xid = new Strings($message->getXid());
            $bytes = $xid->getBytes();
            $count = count($bytes);
            $buffer->putUShort($count);
            if ($count > 0) {
                foreach ($bytes as $byte) {
                    $buffer->putUByte($byte);
                }
            }
        } else {
            $buffer->putUShort(0);
        }

        // Branch Id
        $buffer->putULong($message->getBranchId());

        // Branch Type
        $buffer->putUByte($message->getBranchType());

        // Resource Id
        if ($message->getResourceId() !== null) {
            $resourceId = new Strings($message->getResourceId());
            $bytes = $resourceId->getBytes();
            $count = count($bytes);
            $buffer->putUShort($count);
            if ($count > 0) {
                foreach ($bytes as $byte) {
                    $buffer->putUByte($byte);
                }
            }
        } else {
            $buffer->putUShort(0);
        }

        // Application Data
        if ($message->getApplicationData() !== null) {
            $applicationData = new Strings($message->getApplicationData());
            $bytes = $applicationData->getBytes();
            $count = count($bytes);
            $buffer->putUInt($count);
            if ($count > 0) {
                foreach ($bytes as $byte) {
                    $buffer->putUByte($byte);
                }
            }
        } else {
            $buffer->putUInt(0);
        }

        return $buffer;
    }

    public function decode(AbstractMessage $message, ByteBuffer $buffer): AbstractMessage
    {
        if (! $message instanceof AbstractBranchEndRequest) {
            throw new \InvalidArgumentException('Invalid message');
        }

        // Xid
        $xidLength = 0;
        if ($buffer->remaining() >= 2) {
            $xidLength = $buffer->readUShort();
        }
        if ($xidLength <= 0) {
            return $message;
        }
        if ($buffer->remaining() < $xidLength) {
            return $message;
        }
        $message->setXid($buffer->readString($xidLength));

        // Branch Id
        if ($buffer->remaining() < 8) {
            return $message;
        }
        $message->setBranchId($buffer->readULong());

        // Branch Type
        if ($buffer->remaining() < 1) {
            return $message;
        }
        $message->setBranchType($buffer->readUByte());

        // Resource Id
        $resourceIdLength = 0;
        if ($buffer->remaining() >= 2) {
            $resourceIdLength = $buffer->readUShort();
        }
        if ($resourceIdLength <= 0) {
            return $message;
        }
        if ($buffer->remaining() < $resourceIdLength) {
            return $message;
        }
        $message->setResourceId($buffer->readString($resourceIdLength));

        // Application Data
        $applicationDataLength = 0;
        if ($buffer->remaining() >= 4) {
            $applicationDataLength = $buffer->readUInt();
        }
        if ($applicationDataLength <= 0) {
            return $message;
        }
        if ($buffer->remaining() < $applicationDataLength) {
            return $message;
        }
        $message->setApplicationData($buffer->readString($applicationDataLength));

        return $message;
    }
}
