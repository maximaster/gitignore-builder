<?php

namespace Maximaster\Composer\Plugin\GitignoreBuilder;

use Composer\IO\IOInterface;
use Symfony\Component\Console\Output\Output;

class OutputProxy extends Output
{
    /**
     * @var IOInterface
     */
    protected $io;

    public function setComposerIO(IOInterface $io)
    {
        $this->io = $io;
        return $this;
    }

    protected function doWrite($message, $newline)
    {
        $this->io->write($message, $newline);
    }
}
