<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Encrypted implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if($value != null && $value != ""){
            try {
                return Crypt::decryptString($value);
            } catch (\Throwable $th) {
                return $value;
            }
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if($value == null || $value == ""){
            return ($value);
        }
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        return Crypt::encryptString($value);
    }
}
