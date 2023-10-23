<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('base64img', function ($attribute, $value, $parameters, $validator) {
            $explode = explode(',', $value);
            $allow = ['png', 'jpg', 'jpeg', 'svg'];
            $format = str_replace(
                [
                    'data:image/',
                    ';',
                    'base64',
                ],
                [
                    '', '', '',
                ],
                $explode[0]
            );
            // check format
            if (!in_array($format, $allow)) {
                return false;
            }
            // check base64
            if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                return false;
            }
            return true;
        });

        Validator::extend('base64pdf', function ($attribute, $value, $parameters, $validator) {
            // Ensure the value is a base64-encoded string
            if (!Str::startsWith($value, 'data:application/pdf;base64,')) {
                return false;
            }

            // Decode the base64 string and check if it's a valid PDF
            $decodedValue = base64_decode(substr($value, strpos($value, ',') + 1));
            if ($decodedValue === false) {
                return false;
            }

            // Check if the decoded value starts with the PDF file header "%PDF"
            if (substr($decodedValue, 0, 4) === '%PDF') {
                return true;
            }

            return false;
        });
    }
}
