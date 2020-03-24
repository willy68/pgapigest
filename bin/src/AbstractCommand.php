<?php

namespace Application\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractCommand extends SymfonyCommand
{
    protected $controllerDir = null;

    public function __construct()
    {
        $this->controllerDir = dirname(
            dirname(
            __DIR__)) .
            DIRECTORY_SEPARATOR .
            'generated_controllers'
        ;
        parent::__construct();
    }

    /**
     * create dir
     *
     * @param string $dir
     * @param OutputInterface $output
     * @return int
     */
    protected function createDir(string $dir, OutputInterface $output): int
    {
        if (!is_dir($dir)) {
            $oldumask = umask(0);
            if (!mkdir($dir, 0777, true)) {
                umask($oldumask);
                $output->writeln('Impossible de créer le dossier ' . $dir);
                return -1;
            }
            umask($oldumask);
            $output->writeln("Creation du dossier " . $dir);
        }
        return 0;
    }

    /**
     *
     *
     * @param string $model
     * @param string $filename
     * @param OutputInterface $output
     * @return int
     */
    protected function saveFile(string $model, string $filename, OutputInterface $output): int
    {
        if (!file_exists($filename)) {
            if (($handle = fopen($filename, 'x'))) {
                fwrite($handle, $model);
                fclose($handle);
                chmod($filename, 0666);
                $output->writeln("Ecriture du fichier " . $filename);
                return 0;
            }
        } else {
            $output->writeln("Le fichier " . $filename . " existe déjà, opération non permise");
            return -1;
        }
    }
}
