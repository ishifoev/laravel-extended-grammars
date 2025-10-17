<?php

namespace Ishifoev\ExtendedGrammars\Factories;

use Illuminate\Database\ConnectionInterface;
use Ishifoev\ExtendedGrammars\Grammars\MySqlGrammar;
use Ishifoev\ExtendedGrammars\Grammars\PostgresGrammar;
use Ishifoev\ExtendedGrammars\Grammars\SQLiteGrammar;

final class GrammarFactory
{
    public static function make(ConnectionInterface $connection): object
    {
        return match ($connection->getDriverName()) {
            'mysql' => new MySqlGrammar,
            'pgsql' => new PostgresGrammar,
            'sqlite' => new SQLiteGrammar,
            default => throw new \RuntimeException('Unsupported grammar driver: '.$connection->getDriverName()),
        };
    }
}
