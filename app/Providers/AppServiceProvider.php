<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Helpers\BreadcrumbHelper;
use App\Helpers\ImageHelper;
use App\Models\Product;
use App\Observers\ProductObserver;

/**
 * Class AppServiceProvider
 *
 * The main service provider for the application.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('breadcrumb', function () {
            return new BreadcrumbHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        
        View::composer('*', function ($view) {
            $breadcrumbs = BreadcrumbHelper::generate();
            $view->with('breadcrumbs', $breadcrumbs);
        });
        
        Blade::directive('seoImage', function ($expression) {
            return "<?php echo App\\Helpers\\ImageHelper::optimizedImage($expression); ?>";
        });
        
        Blade::directive('productImage', function ($expression) {
            return "<?php echo App\\Helpers\\ImageHelper::productImage($expression); ?>";
        });
        
        Blade::directive('breadcrumbs', function () {
            return "<?php echo view('partials.breadcrumbs', compact('breadcrumbs'))->render(); ?>";
        });
        
        Blade::directive('structuredData', function ($expression) {
            return "<?php if($expression): ?><script type='application/ld+json'><?php echo json_encode($expression, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script><?php endif; ?>";
        });
    }
}
