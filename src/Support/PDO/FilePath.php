<?php

namespace PHPCodex\Framework\Support\PDO;

/**
 * Class FilePath
 * @package PHPCodex\Support\PDO
 *
 * This PHP Data Object will hold the path
 * of our file and some additional info.
 */
class FilePath
{

    const DIRECTORY_ELEMENT = 'dirname';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array|mixed
     * This will hold some information about the
     * file we have been given. As this has not
     * been modeled, it could change.
     */
    protected $data = [];

    public function __construct(string $path)
    {
        $this->path = $path;

        if ($this->exists()) {
            $this->data = pathinfo($this->path);
        }
    }

    /**
     * @return string
     *
     * Get our file as it is protected.
     */
    public function get() : string
    {
        return $this->path;
    }

    /**
     * @return bool
     *
     * Validate whether our file exists or not. This
     * is currently assuming your file-system
     * has access to the location.
     */
    public function exists() : bool
    {
        return file_exists($this->path);
    }

    /**
     * @return FilePath
     *
     * We may want to traverse back up the directories.
     * This implementation allows this.
     */
    public function parent()
    {
        return new FilePath($this->data[FilePath::DIRECTORY_ELEMENT]);
    }
}