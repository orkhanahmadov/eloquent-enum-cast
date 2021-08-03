<?php

namespace Orkhanahmadov\EloquentEnumCast;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MyCLabs\Enum\Enum;

abstract class EnumCast extends Enum implements Castable
{
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes {
            /**
             * @param  \Illuminate\Database\Eloquent\Model  $model
             * @param  string  $key
             * @param  array  $value
             * @param  array  $attributes
             */
            public function get($model, $key, $value, $attributes): Enum
            {
                $enum = $model->getCasts()[$key];

                return $enum::from($value);
            }

            public function set($model, $key, $value, $attributes)
            {
                if (! $value instanceof EnumCast) {
                    throw new Exception(
                        "{$key} must be instance of \"Orkhanahmadov\EloquentEnumCast\EnumCast\""
                    );
                }

                return $value->getValue();
            }
        };
    }
}

