<?php

declare(strict_types=1);

namespace Rinvex\Language\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        // Add language validation rule
        Validator::extend('language', fn($attribute, $value): bool => is_string($value) && mb_strlen($value) === 2 && array_key_exists(mb_strtolower($value), languages()), __('validation.invalid_language'));
    }
}
