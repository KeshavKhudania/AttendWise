<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class EncryptAndHash implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            // Store null in both columns if value is empty
            return [
                $key => null,
                $key . '_hash' => null,
            ];
        }

        $hashColumn = $key . '_hash';

        return [
            // Encrypted value stored in main column (email/mobile)
            $key => Crypt::encryptString($value),

            // Hash stored in email_hash / mobile_hash
            $hashColumn => search_hash($value),
        ];
    }
}
