<?php

namespace Ishifoev\ExtendedGrammars\Contracts;

use Illuminate\Database\Query\Builder;

interface InsertOrUpdateGrammarInterface
{
    public function compileInsertOrUpdateUsing(
        Builder $query,
        array $columns,
        Builder $select,
        array $updates
    ): string;
}
