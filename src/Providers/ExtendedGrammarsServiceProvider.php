<?php

namespace Ishifoev\ExtendedGrammars\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Ishifoev\ExtendedGrammars\Grammars;

class ExtendedGrammarsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        DB::extend('mysql', function ($config, $name) {
            /** @var \Illuminate\Database\MySqlConnection $connection */
            $connection = app('db.factory')->make($config, $name);
            $connection->setQueryGrammar(new Grammars\MySqlGrammar);

            return $connection;
        });

        DB::extend('pgsql', function ($config, $name) {
            $connection = app('db.factory')->make($config, $name);
            $connection->setQueryGrammar(new Grammars\PostgresGrammar);

            return $connection;
        });

        DB::extend('sqlite', function ($config, $name) {
            $connection = app('db.factory')->make($config, $name);
            $connection->setQueryGrammar(new Grammars\SQLiteGrammar);

            return $connection;
        });

        Builder::macro('insertOrUpdateUsing', function (array $columns, Builder $select, array $updates): bool {
            /** @var Builder $this */
            $grammar = $this->getGrammar();

            if (! method_exists($grammar, 'compileInsertOrUpdateUsing')) {
                throw new \BadMethodCallException(
                    sprintf('%s does not support insertOrUpdateUsing()', $grammar::class)
                );
            }

            $sql = $grammar->compileInsertOrUpdateUsing($this, $columns, $select, $updates);

            return $this->getConnection()->statement($sql);
        });
    }
}
