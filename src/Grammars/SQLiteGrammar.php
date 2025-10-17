<?php

namespace Ishifoev\ExtendedGrammars\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as BaseSQLiteGrammar;
use Ishifoev\ExtendedGrammars\Contracts\InsertOrUpdateGrammarInterface;

class SQLiteGrammar extends BaseSQLiteGrammar implements InsertOrUpdateGrammarInterface
{
    public function compileInsertOrUpdateUsing(Builder $query, array $columns, Builder $select, array $updates): string
    {
        $insert = $this->compileInsertUsing($query, $columns, $select->toSql());
        $update = collect($updates)
            ->map(fn ($col) => "{$this->wrap($col)} = excluded.{$this->wrap($col)}")
            ->implode(', ');

        return "{$insert} ON CONFLICT(id) DO UPDATE SET {$update}";
    }
}
