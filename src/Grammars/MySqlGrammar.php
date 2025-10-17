<?php

namespace Ishifoev\ExtendedGrammars\Grammars;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar as BaseMySqlGrammar;
use Ishifoev\ExtendedGrammars\Contracts\InsertOrUpdateGrammarInterface;

class MySqlGrammar extends BaseMySqlGrammar implements InsertOrUpdateGrammarInterface
{
    public function compileInsertOrUpdateUsing(Builder $query, array $columns, Builder $select, array $updates): string
    {
        $insert = $this->compileInsertUsing($query, $columns, $select->toSql());
        $update = collect($updates)
            ->map(fn ($col) => "{$this->wrap($col)} = VALUES({$this->wrap($col)})")
            ->implode(', ');

        return "{$insert} ON DUPLICATE KEY UPDATE {$update}";
    }
}
