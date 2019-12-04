<?php
declare(strict_types=1);

namespace Funivan\CabbageCore\Fs\Tests\Fs\BlackHole;

use BadMethodCallException;
use Funivan\CabbageCore\Fs\FileStorageInterface;
use Funivan\CabbageCore\Fs\Fs\BlackHole\BlackHoleStorage;
use Funivan\CabbageCore\Fs\Fs\Local\LocalPath;
use PHPUnit\Framework\TestCase;

/**
 * @codeCoverageIgnore
 */
final class BlackHoleStorageTest extends TestCase
{
    public function testDummyWrite(): void
    {
        $storage = new BlackHoleStorage();
        $path = new LocalPath('/test.txt');
        $storage->write($path, 'data');
        self::assertSame(
            FileStorageInterface::TYPE_UNKNOWN,
            $storage->type($path)
        );
    }


    public function testFind(): void
    {
        self::assertSame(
            0,
            iterator_count(
                (new BlackHoleStorage())->finder(
                    new LocalPath('/')
                )->items()
            )

        );
    }


    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage "Read" operation is not supported by this adapter
     */
    public function testRead(): void
    {
        (new BlackHoleStorage())->read(new LocalPath('/document.txt'));
    }
}
