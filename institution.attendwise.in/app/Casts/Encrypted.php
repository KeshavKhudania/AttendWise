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
        if ($value != null && $value != "") {
            try {
                $decrypted = Crypt::decryptString($value);
                // Check if the decrypted string is a serialized PHP string
                // Serialized strings usually start with s: for string, a: for array, O: for object, etc.
                if (is_string($decrypted) && preg_match('/^(i|s|a|O|b|d):?\d*[:{]/', $decrypted)) {
                    try {
                        $unserialized = unserialize($decrypted);
                        if ($unserialized !== false || $decrypted === serialize(false)) {
                            return $unserialized;
                        }
                    } catch (\Throwable $th) {
                        // Not a valid serialized string, but it matches the regex (unlikely)
                    }
                }
                return $decrypted;
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
