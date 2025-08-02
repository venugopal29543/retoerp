<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\PermissionService;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Feature checking directive
        Blade::directive('hasFeature', function ($expression) {
            return "<?php if(app('" . PermissionService::class . "')->hasFeature({$expression})): ?>";
        });

        Blade::directive('endHasFeature', function () {
            return '<?php endif; ?>';
        });

        // Permission checking directive
        Blade::directive('canDo', function ($expression) {
            return "<?php if(app('" . PermissionService::class . "')->canPerformAction({$expression})): ?>";
        });

        Blade::directive('endCanDo', function () {
            return '<?php endif; ?>';
        });

        // Feature config directive
        Blade::directive('featureConfig', function ($expression) {
            return "<?php echo json_encode(app('" . PermissionService::class . "')->getFeatureConfig({$expression})); ?>";
        });

        // Field permission directive
        Blade::directive('canEditField', function ($expression) {
            list($form, $field) = explode(',', str_replace(['\'', '"', ' '], '', $expression));
            return "<?php if(app('" . PermissionService::class . "')->getFieldPermissions('{$form}', '{$field}')['write']): ?>";
        });

        Blade::directive('endCanEditField', function () {
            return '<?php endif; ?>';
        });

        // UI Config directive
        Blade::directive('uiConfig', function () {
            return "<?php echo json_encode(app('" . PermissionService::class . "')->getUIConfig()); ?>";
        });
    }
}
