<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractCommand extends SymfonyCommand
{
    protected $controllerDir = null;

    protected $query = "SHOW TABLES FROM ";

    protected $db = null;

    /**
     * pdo instance
     *
     * @var \PDO
     */
    protected $dao = null;

    public function __construct(ContainerInterface $c)
    {
        $this->dao = $c->get(\PDO::class);
        $this->db = $c->get('database.name');
        $this->controllerDir = dirname(
            dirname(
            __DIR__)) .
            DIRECTORY_SEPARATOR .
            'generated_controllers'
        ;
        parent::__construct();
    }

    /**
     * Undocumented function
     *
     * @param string $query
     * @return \PDOStatement|bool
     */
    protected function getTables(string $query)
    {
        $tables = $this->dao->query($query);
        return $tables;
    }
  
    /**
     * Undocumented function
     *
     * @param string $query
     * @return \PDOStatement|bool
     */
    protected function getColumns(string $query)
    {
        $columns = $this->dao->query($query);
        return $columns;
    }
  
    /**
     * Undocumented function
     *
     * @param string $db
     * @param string $table
     * @return string
     */
    protected function getColumnsQuery(string $db, string $table): string
    {
        return "SELECT COLUMN_NAME
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = '{$db}' AND TABLE_NAME = '{$table}'
      AND COLUMN_NAME NOT IN ('id','created_at','updated_at','password')";
    }
  
    /**
     *
     *
     * @param string $sql
     * @return array
     */
    protected function getColumnsArray(string $sql): array
    {
        $columns = $this->getColumns($sql);
        $cols = array();
        while ($column = $columns->fetch(\PDO::FETCH_ASSOC)) {
            $cols[] = $column['COLUMN_NAME'];
        }
        return $cols;
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
