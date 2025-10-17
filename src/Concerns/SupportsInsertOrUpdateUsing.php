<?php

namespace Ishifoev\ExtendedGrammars\Concerns;

use Illuminate\Database\Query\Builder;

trait SupportsInsertOrUpdateUsing
{
    public static function insertOrUpdateUsing(array $columns, Builder $select, array $updates): bool
    {
        $instance = new static;
        $grammar = $instance->getConnection()->getQueryGrammar();
        $table = $instance->getTable();

        $sql = $grammar->compileInsertOrUpdateUsing(
            $instance->newQuery()->from($table),
            $columns,
            $select,
            $updates
        );

        return $instance->getConnection()->statement($sql);
    }
}
