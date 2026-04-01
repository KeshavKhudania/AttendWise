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
if (! function_exists('get_logged_in_user')) {
    /**
     * Get the currently logged-in admin user
     *
     * @return \App\Models\AdminUser|null
     */
    function get_logged_in_user()
    {
        $record = AdminUser::find(Crypt::decrypt(Session::get('user_id')));
        if ($record != null) {
            return $record;
        }
        return false;
    }
}
