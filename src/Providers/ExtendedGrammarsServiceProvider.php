<?php

namespace Ishifoev\ExtendedGrammars\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Ishifoev\ExtendedGrammars\Grammars;

class ExtendedGrammarsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Переопределяем стандартные грамматики Laravel
        $this->app->bind(MySqlGrammar::class, Grammars\MySqlGrammar::class);
        $this->app->bind(PostgresGrammar::class, Grammars\PostgresGrammar::class);
        $this->app->bind(SQLiteGrammar::class, Grammars\SQLiteGrammar::class);

        // Добавляем macro для Query Builder — поддержка DB::table()
        Builder::macro('insertOrUpdateUsing', function (array $columns, Builder $select, array $updates): bool {
            /** @var Builder $this */
            $grammar = $this->getGrammar();
            $sql = $grammar->compileInsertOrUpdateUsing($this, $columns, $select, $updates);

            return $this->getConnection()->statement($sql);
        });
    }
}
