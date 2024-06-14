<?php

namespace Vcian\LaravelDBAuditor\Queries;

use Illuminate\Support\Facades\DB;

class DatabaseTableFieldsClass
{
    public function __construct(protected string $driver, protected string $database, protected string $table)
    {
    }


    public function __invoke(): array
    {
        return match ($this->driver) {
            'sqlite' => $this->sqlite(),
            default => $this->mysql(),
        };
    }

    /**
     * @return array
     */
    public function sqlite(): array
    {
        $fields = $this->select("PRAGMA table_info(`$this->table`)");
        return array_column($fields,'name');
    }

    /**
     * @param $query
     * @return array
     */
    public function select($query): array
    {
        return DB::select($query);
    }

    /**
     * @return array
     */
    public function mysql(): array
    {
        $fields = $this->select("Describe `$this->table`");
        return array_column($fields,'Field');
    }
}
