<?php

use App\Models\AdminUser;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

if (! function_exists('search_hash')) {
    /**
     * Generate a normalized hash for searchable encrypted fields
     *
     * @param  string|null  $value
     * @return string|null
     */
    function search_hash(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return hash('sha256', strtolower(trim($value)));
    }
}
