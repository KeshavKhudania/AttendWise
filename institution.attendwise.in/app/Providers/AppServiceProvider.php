<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        Builder::macro('whereEncrypted', function (string $field, $value) {
            /** @var \Illuminate\Database\Eloquent\Builder $this */
            return $this->where($field . '_hash', search_hash($value));
        });

        Builder::macro('whereEncryptedEmail', function ($value) {
            return $this->where('email_hash', search_hash($value));
        });

        Builder::macro('whereEncryptedMobile', function ($value) {
            return $this->where('mobile_hash', search_hash($value));
        });
    }
}
