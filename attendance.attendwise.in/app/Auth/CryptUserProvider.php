<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Crypt;

class CryptUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            array_key_exists('password', $credentials))) {
            return null;
        }

        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (str_contains($key, 'password')) {
                continue;
            }

            // If the field is one of our special ones, search by hash
            if ($key === 'email' || $key === 'mobile') {
                $query->where($key . '_hash', search_hash($value));
            } else {
                if (is_array($value) || $value instanceof \Illuminate\Contracts\Support\Arrayable) {
                    $query->whereIn($key, $value);
                } else {
                    $query->where($key, $value);
                }
            }
        }

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        
        // The password field is cast to 'Encrypted', so it's already decrypted by Eloquent
        return $plain === $user->getAuthPassword();
    }
}
