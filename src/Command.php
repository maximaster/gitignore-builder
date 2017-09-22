<?php

namespace Maximaster\Composer\Plugin\GitignoreBuilder;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected $defaults = [
        'template-file' => 'template.gitignore',
        'project-dir' => '',
    ];

    protected function configure()
    {
        $this->setName('gitignore-builder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $workDir = getcwd();
        $configFile = $workDir.'/composer.json';
        if (!is_file($configFile)) {
            $output->writeln("Can't find composer config");
            return;
        }

        $config = json_decode(file_get_contents($configFile), true);
        if ($config === null) {
            $output->writeln("Broken composer.json");
            return;
        }

        if (!isset($config['extra'])) {
            $output->writeln("Extra not found in composer.json");
            return;
        }

        $extra = $config['extra'];
        $cfg = array_merge($this->defaults, isset($extra['gitignore-builder']) ? $extra['gitignore-builder'] : []);

        $cfg['project-dir'] = realpath($workDir.DIRECTORY_SEPARATOR.$cfg['project-dir']);

        $templatePath = $cfg['project-dir'].DIRECTORY_SEPARATOR.$cfg['template-file'];

        if (!is_file($templatePath)) {
            return;
        }

        try {
            $ignoreTemplate = new Template($templatePath, $cfg['project-dir']);
            if ($ignoreTemplate->save() === false) {
                throw new Exception("Can't write .gitignore file");
            }

            $output->writeln(".gitignore successfully generated");
        } catch (FileNotFoundException $e) {
            $output->writeln("File not found: {$e->getMessage()}");
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }
    }
}
