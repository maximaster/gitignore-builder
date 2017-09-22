<?php

namespace Maximaster\Composer\Plugin\GitignoreBuilder;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Console\Input\ArrayInput;

class GitignoreBuilderPlugin implements PluginInterface
{
    /**
     * @var array
     */
    protected $cfg;

    public function activate(Composer $composer, IOInterface $io)
    {
        $cmd = new Command;
        $cmd->run(new ArrayInput([]), (new OutputProxy)->setComposerIO($io));
    }
}
