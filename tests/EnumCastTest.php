<?php

declare(strict_types=1);

namespace Orkhanahmadov\EloquentEnumCast\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use Orkhanahmadov\EloquentEnumCast\EnumCast;
use UnexpectedValueException;

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

    public function testWhenSavingAndPassingRawValueInsteadOfEnumItWillCastItGetTheValueEnum(): void
    {
        $model = new TestModel();
        $model->enum = 'value1';
        $model->save();

        $this->assertCount(1, TestModel::get());
        $this->assertSame('value1', DB::table('test_models')->first()->enum);
    }

    public function testSavesRawValueWithStrictModeTurnedOff(): void
    {
        $model = new NonStrictTestModel();
        $model->enum = '1';
        $model->save();

        $this->assertNotNull($saved = NonStrictTestModel::first());
        $this->assertSame(1, $saved->enum->getValue());
    }

    public function testThrowsExceptionWhenInvalidRawValueIsPassed(): void
    {
        $this->expectException(UnexpectedValueException::class);

        $model = new TestModel();
        $model->enum = 'invalid-value';
        $model->save();
    }

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE test_models (enum VARCHAR);');
        DB::statement('CREATE TABLE non_strict_test_models (enum INT);');
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

class NonStrictTestEnum extends EnumCast
{
    protected const STRICT_MODE = false;
    private const KEY1 = 1;
}

class NonStrictTestModel extends Model
{
    protected $casts = [
        'enum' => NonStrictTestEnum::class,
    ];
    protected $guarded = [];
    public $timestamps = false;
}