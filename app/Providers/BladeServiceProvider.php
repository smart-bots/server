<?php

namespace SmartBots\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('continue', function($expression) {
            return '<?php continue; ?>';
        });

        Blade::directive('break', function($expression) {
            return '<?php break; ?>';
        });

        // Add @optional for Complex Yielding
        Blade::directive('optional', function($expression) {
            return "<?php if(trim(\$__env->yieldContent{$expression})): ?>";
        });

        // Add @endoptional for Complex Yielding
        Blade::directive('endoptional', function($expression) {
            return "<?php endif; ?>";
        });

        // Add @set for Variable Assignment
        Blade::directive('set', function($expression) {
            // Strip Open and Close Parenthesis
            if(Str::startsWith($expression, '('))
                $expression = substr($expression, 1, -1);

            // Break the Expression into Pieces
            $segments = explode(',', $expression, 2);

            // Return the Conversion
            return "<?php " . $segments[0] . " = " . $segments[1] . "; ?>";
        });

        // Add @dd for dd laravel function
        Blade::directive('dd', function ($expression) {
            return "<?php dd(with{$expression}); ?>";
        });

        // Add @asset for asset laravel function
        Blade::directive('asset', function ($expression) {
            // dd($expression);
            return "<?php echo asset$expression; ?>";
        });

        // Add @route for route laravel function
        Blade::directive('route', function ($expression) {
            // dd($expression);
            return "<?php echo route$expression; ?>";
        });

        // Add @trans for route laravel function
        Blade::directive('trans', function ($expression) {
            // dd($expression);
            return "<?php echo trans$expression; ?>";
        });

        //------------------------------------------------------------------

        Blade::directive('header', function ($expression) {
            // dd($expression);
            return "<?php echo content_header$expression; ?>";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
