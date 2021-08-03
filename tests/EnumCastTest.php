<?php

declare(strict_types=1);

namespace Orkhanahmadov\EloquentEnumCast\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use Orkhanahmadov\EloquentEnumCast\EnumCast;

class EnumCastTest extends TestCase
{
    public function testCastsValueToEnumClassWhenRetrievingModelFromDatabase(): void
    {
        DB::table('test_models')->insert(['enum' => 'value2']);

        $model = TestModel::first();
        $this->assertInstanceOf(TestEnum::class, $model->enum);
        $this->assertSame('value2', $model->enum->getValue());
    }

    public function testCastsEnumValueToDatabaseWhenSavingModel(): void
    {
        $model = new TestModel();
        $model->enum = TestEnum::KEY1();
        $model->save();

        $this->assertCount(1, TestModel::get());
        $this->assertSame('value1', DB::table('test_models')->first()->enum);
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE test_models (enum VARCHAR);');
    }
}

class TestEnum extends EnumCast
{
    private const KEY1 = 'value1';
    private const KEY2 = 'value2';
}

class TestModel extends Model
{
    protected $casts = [
        'enum' => TestEnum::class,
    ];
    protected $guarded = [];
    public $timestamps = false;
}