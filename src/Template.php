<?php

namespace Maximaster\Composer\Plugin\GitignoreBuilder;

use Composer\Json\JsonValidationException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Template
{
    protected $src;

    protected $projectDir;

    public function __construct($src, $projectDir)
    {
        if (!is_file($src)) {
            throw new FileNotFoundException($src);
        }

        $this->src = $src;

        if (!is_dir($projectDir)) {
            throw new FileNotFoundException($projectDir);
        }
        $this->projectDir = $projectDir;
    }

    public function save($relTargetFile = null)
    {
        if ($relTargetFile === null) {
            $relTargetFile = '.gitignore';
        }

        $targetFile = $this->projectDir.DIRECTORY_SEPARATOR.$relTargetFile;
        
        $ignoreData = preg_replace_callback('/^##import\s*(.+)/m', function ($m) {
            $root = false;
            $file = $target = trim($m[1]);
            if (strpos($target, '{') === 0) {
                $targetCfg = json_decode($target, true);
                if ($targetCfg === null) {
                    throw new JsonValidationException($target);
                }

                $file = $targetCfg['file'];
                if (isset($targetCfg['root'])) {
                    $root = $targetCfg['root'];
                }
            }


            $file = $this->projectDir.DIRECTORY_SEPARATOR.$file;
            if (!is_file($file)) {
                throw new FileNotFoundException($file);
            }

            $wrap = str_repeat('#', strlen($m[0]) - 1);
            return $wrap.PHP_EOL.$m[0].$wrap.PHP_EOL.$this->importFile($file, $root);
        }, file_get_contents($this->src));

        $ignoreData .= "/{$relTargetFile}";

        return file_put_contents($targetFile, $ignoreData);
    }

    protected function importFile($file, $root = null)
    {
        $data = file_get_contents($file);
        if ($root) {
            $data = preg_replace('~^/~m', '$0'.trim($root, '/\\').'/', $data);
        }

        return $data;
    }
}
