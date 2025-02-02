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
namespace Hyperf\Seata\Logger;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class StdoutLogger implements LoggerInterface
{
    protected ConfigInterface $config;

    protected OutputInterface $output;

    protected array $tags = [
        'class',
    ];

    private string $className;

    public function __construct(ConfigInterface $config, $output = null)
    {
        $this->config = $config;
        $this->output = $output ? $output : new ConsoleOutput();
    }

    /**
     * {@inheritdoc}
     */
    public function emergency(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function alert(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function critical(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function error(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function warning(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function notice(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function info(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function debug(\Stringable|string $message, array $context = []):void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level,\Stringable|string  $message, array $context = []):void
    {
        $config = $this->config->get(LoggerInterface::class, $this->config->get(StdoutLoggerInterface::class));
        if (! in_array($level, $config['log_level'], true)) {
            return;
        }
        $keys = array_keys($context);
        $tags = [];
        foreach ($keys as $k => $key) {
            if (in_array($key, $this->tags, true)) {
                $tags[$key] = $context[$key];
                unset($keys[$k]);
            }
        }

        $search = array_map(function ($key) {
            return sprintf('{%s}', $key);
        }, $keys);

        $message = str_replace($search, $context, $this->getMessage((string) $message, $level, $tags));
        $message = sprintf('[%s]', $this->className) . ' ' . $message;
        $this->output->writeln($message);
    }

    public function setClass(string $class)
    {
        $this->className = $class;
    }

    protected function getMessage(string $message, string $level = LogLevel::INFO, array $tags = [])
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
                $tag = 'error';
                break;
            case LogLevel::ERROR:
                $tag = 'fg=red';
                break;
            case LogLevel::WARNING:
            case LogLevel::NOTICE:
                $tag = 'comment';
                break;
            case LogLevel::INFO:
            default:
                $tag = 'info';
        }

        $template = sprintf('<%s>[%s]</>', $tag, strtoupper($level));
        $implodedTags = '';
        foreach ($tags as $value) {
            $implodedTags .= (' [' . $value . ']');
        }

        return sprintf($template . $implodedTags . ' %s', $message);
    }
}
