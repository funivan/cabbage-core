<?php

declare(strict_types=1);

namespace Funivan\CabbageCore\Fs\Fs\Memory;

use Funivan\CabbageCore\Fs\Exception\ReadException;
use Funivan\CabbageCore\Fs\FileStorageInterface;
use Funivan\CabbageCore\Fs\Finder\FinderInterface;
use Funivan\CabbageCore\Fs\Finder\InMemoryPathFinder;
use Funivan\CabbageCore\Fs\Fs\Local\LocalPath;
use Funivan\CabbageCore\Fs\PathInterface;
use InvalidArgumentException;

/**
 * Does not support directory operations
 */
class MemoryStorage implements FileStorageInterface
{

    /**
     * Store plain content in the memory
     *
     * @var array Array<String, String>
     */
    private $files = [];


    /**
     * @param PathInterface $path
     * @return FinderInterface
     */
    final public function finder(PathInterface $path): FinderInterface
    {
        return new InMemoryPathFinder(array_keys($this->files), $path);
    }


    /**
     * @param PathInterface $path
     * @param string $name
     * @return string
     */
    final public function meta(PathInterface $path, string $name): string
    {
        if ('extension' === $name) {
            return pathinfo($path->name(), PATHINFO_EXTENSION);
        }
        throw new InvalidArgumentException('Unsupported meta key');
    }


    /**
     * @param PathInterface $path
     * @return string
     */
    final public function type(PathInterface $path): string
    {
        $result = FileStorageInterface::TYPE_UNKNOWN;
        if (array_key_exists($path->assemble(), $this->files)) {
            $result = FileStorageInterface::TYPE_FILE;
        } else {
            foreach (array_keys($this->files) as $filePath) {
                if ((new LocalPath($filePath))->previous()->equal($path)) {
                    $result = FileStorageInterface::TYPE_DIRECTORY;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * @param PathInterface $old
     * @param PathInterface $new
     * @throws ReadException
     */
    final public function move(PathInterface $old, PathInterface $new): void
    {
        $this->write($new, $this->read($old));
        $this->remove($old);
    }

    /**
     * @param PathInterface $path
     * @param string $data
     */
    final public function write(PathInterface $path, string $data): void
    {
        $filePath = $path->assemble();
        $this->files[$filePath] = $data;
    }

    /**
     * @param PathInterface $path
     * @return string
     * @throws ReadException
     */
    final public function read(PathInterface $path): string
    {
        $filePath = $path->assemble();
        if (!array_key_exists($filePath, $this->files)) {
            throw new ReadException(sprintf('Can not read file contents : %s', $filePath));
        }
        return (string)$this->files[$filePath];
    }

    /**
     * @param PathInterface $path
     */
    final public function remove(PathInterface $path): void
    {
        unset($this->files[$path->assemble()]);
    }
}
