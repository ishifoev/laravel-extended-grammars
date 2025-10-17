<?php

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor;
use Ishifoev\ExtendedGrammars\Grammars\MySqlGrammar;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class MySqlGrammarTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function test_compile_insert_or_update_using(): void
    {
        $connection = m::mock(\Illuminate\Database\Connection::class);
        $grammar = new MySqlGrammar($connection);

        $connection->shouldReceive('getPostProcessor')->andReturn(new Processor);
        $connection->shouldReceive('getTablePrefix')->andReturn('');
        $connection->shouldReceive('getQueryGrammar')->andReturn($grammar);

        $builder = new Builder($connection, $grammar);
        $builder->from('users');

        $select = clone $builder;
        $select->from('imports')->select('id', 'name', 'email');

        $sql = $grammar->compileInsertOrUpdateUsing(
            $builder,
            ['id', 'name', 'email'],
            $select,
            ['name', 'email']
        );

        $this->assertStringStartsWith('insert into', strtolower($sql));
        $this->assertStringContainsString('select', strtolower($sql));
        $this->assertStringContainsString('on duplicate key update', strtolower($sql));
        $this->assertMatchesRegularExpression('/values\s*\(`?name`?\)/i', $sql);
        $this->assertMatchesRegularExpression('/values\s*\(`?email`?\)/i', $sql);
    }
}
