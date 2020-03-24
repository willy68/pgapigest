<?php

namespace Application\Console;

use Psr\Container\ContainerInterface;

trait DatabaseCommandTrait
{

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

    protected function getDao(ContainerInterface $c): \PDO
    {
        if (!$this->dao) {
            $this->dao = $c->get(\PDO::class);
        }
        return $this->dao;
    }
}
