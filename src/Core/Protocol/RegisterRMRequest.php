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
namespace Hyperf\Seata\Core\Protocol;

class RegisterRMRequest extends AbstractIdentifyRequest
{
    private string $resourceIds = '';

    public function getTypeCode(): int
    {
        return MessageType::TYPE_REG_RM;
    }

    public function getResourceIds(): string
    {
        return $this->resourceIds;
    }

    public function setResourceIds(string $resourceIds): static
    {
        $this->resourceIds = $resourceIds;
        return $this;
    }
}
