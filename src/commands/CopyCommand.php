<?php

namespace bilberrry\spaces\commands;

use Aws\ResultInterface;
use bilberrry\spaces\base\commands\ExecutableCommand;
use bilberrry\spaces\base\commands\traits\Async;
use bilberrry\spaces\base\commands\traits\Options;
use bilberrry\spaces\interfaces\commands\Asynchronous;
use bilberrry\spaces\interfaces\commands\HasSpace;
use bilberrry\spaces\interfaces\commands\PlainCommand;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class CopyCommand
 *
 * @method ResultInterface|PromiseInterface execute()
 *
 * @package bilberrry\spaces\commands
 */
class CopyCommand extends ExecutableCommand implements PlainCommand, HasSpace, Asynchronous
{
    use Async;
    use Options;

    /** @var array */
    protected $args = [];

    /**
     * @return string
     */
    public function getSpace(): string
    {
        return $this->args['Bucket'] ?? '';
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function inSpace(string $name)
    {
        $this->args['Bucket'] = $name;

        return $this;
    }

    /**
     * @param string $copysource
     *
     * @return $this
     */
    public function withCopySource(string $copysource)
    {
        $this->args['CopySource'] = $copysource;

        return $this;
    }

    /**
     * @param string $filename
     *
     * @return $this
     */
    public function withFilename(string $filename)
    {
        $this->args['Key'] = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopySource(): string
    {
        return $this->args['CopySource'] ?? '';
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->args['Key'] ?? '';
    }

    /**
     * @internal used by the handlers
     *
     * @return string
     */
    public function getName(): string
    {
        return 'CopyObject';
    }

    /**
     * @internal used by the handlers
     *
     * @return array
     */
public function toArgs(): array
{
    $args = array_replace($this->options, $this->args);
    
    // Ensure bucket is set
    $bucket = $args['Bucket'] ?? $this->client->getBucket();
    $args['Bucket'] = $bucket;
    
    // Handle CopySource
    if (isset($args['CopySource'])) {
        $source = $args['CopySource'];
        
        // Remove leading slash if present
        $source = ltrim($source, '/');
        
        // Always prepend bucket name for same-bucket copy
        $fullSource = $bucket . '/' . $source;
        
        // Format as /bucket/key for DigitalOcean
        $args['CopySource'] = '/' . $fullSource;
        
        // IMPORTANT: DigitalOcean needs slashes NOT encoded
        // Only encode everything else
        $args['CopySource'] = '/' . implode('/', array_map('rawurlencode', explode('/', $fullSource)));
    }
    
    // Clean destination
    if (isset($args['Key'])) {
        $args['Key'] = ltrim($args['Key'], '/');
    }
    
    return $args;
}
}
