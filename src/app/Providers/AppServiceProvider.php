<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\ServiceOrder;
use App\Models\Store;
use App\Policies\ActivityPolicy;
use App\Policies\ProductOrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ServiceOrderPolicy;
use App\Policies\StorePolicy;
use App\Services\Contracts\RouteDistanceServiceInterface;
use App\Services\FakeRouteDistanceService;
use App\Services\OsrmRouteDistanceService;
use Filament\Actions\MountableAction;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Volt;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('testing')) {
            $this->app->bind(RouteDistanceServiceInterface::class, FakeRouteDistanceService::class);
        } else {
            $this->app->bind(RouteDistanceServiceInterface::class, OsrmRouteDistanceService::class);
        }

        $this->app->singleton(\App\Services\ShippingManager::class, function ($app) {
            return new \App\Services\ShippingManager($app);
        });

        $this->app->bind(
            \App\Services\Contracts\InstantCourierServiceInterface::class,
            function ($app) {
                return $app->make(\App\Services\ShippingManager::class)->driver();
            }
        );

        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            \App\Http\Responses\FilamentLogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Volt::mount([resource_path('views/livewire')]);

        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Store::class, StorePolicy::class);
        Gate::policy(ServiceOrder::class, ServiceOrderPolicy::class);
        Gate::policy(ProductOrder::class, ProductOrderPolicy::class);
        Gate::policy(\App\Models\ServiceCommission::class, \App\Policies\ServiceCommissionPolicy::class);
        Gate::policy(\App\Models\MarketplaceCommission::class, \App\Policies\MarketplaceCommissionPolicy::class);

        // Model Observers untuk pencatatan otomatis komisi terpisah (AB-005, PK-04)
        ServiceOrder::observe(\App\Observers\ServiceOrderObserver::class);
        ProductOrder::observe(\App\Observers\ProductOrderObserver::class);

        Page::formActionsAlignment(Alignment::Right);
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };
        MountableAction::configureUsing(function (MountableAction $action) {
            $action->modalFooterActionsAlignment(Alignment::Right);
        });
    }
}
