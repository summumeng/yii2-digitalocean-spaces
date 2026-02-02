<?php

namespace bilberrry\spaces\commands;

use Aws\ResultInterface;
use bilberrry\spaces\base\commands\ExecutableCommand;
use bilberrry\spaces\base\commands\traits\Async;
use bilberrry\spaces\interfaces\commands\Asynchronous;
use bilberrry\spaces\interfaces\commands\HasAcl;
use bilberrry\spaces\interfaces\commands\HasSpace;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class UploadCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package bilberrry\spaces\commands
 */
class CopyCommand extends ExecutableCommand implements HasSpace, HasAcl, Asynchronous
{
    use Async;

    /** @var string */
    protected $space;

    /** @var string */
    protected $acl;

    /** @var mixed */
    protected $copysource;

    /** @var string */
    protected $filename;

    /** @var array */
    protected $options = [];

    /**
     * @return string
     */
    public function getSpace(): string
    {
        return (string)$this->space;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function inSpace(string $name)
    {
        $this->space = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcl(): string
    {
        return (string)$this->acl;
    }

    /**
     * @param string $acl
     *
     * @return $this
     */
    public function withAcl(string $acl)
    {
        $this->acl = $acl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCopySource()
    {
        return $this->copysource;
    }

    /**
     * @param mixed $source
     *
     * @return $this
     */
    public function withCopySource($copysource)
    {
        $this->copysource = $copysource;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return (string)$this->filename;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function withFilename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }


    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->getParam('ContentType', '');
    }

    /**
     * @param string $contentType
     *
     * @return $this
     */
    public function withContentType(string $contentType)
    {
        return $this->withParam('ContentType', $contentType);
    }


    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam(string $name, $default = null)
    {
        return $this->options['params'][$name] ?? $default;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function withParam(string $name, $value)
    {
        $this->options['params'][$name] = $value;

        return $this;
    }

    /**
     * @internal used by the handlers
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
