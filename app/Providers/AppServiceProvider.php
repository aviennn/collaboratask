<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Providers\BroadcastServiceProvider;
use App\Services\PointsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Register the BroadcastServiceProvider
        $this->app->register(BroadcastServiceProvider::class);

        // Register PointsService as a singleton
        $this->app->singleton(PointsService::class, function ($app) {
            return new PointsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Share unread notifications with the navigation view
        View::composer('layouts.navigation', function ($view) {
            // Ensure the user is logged in
            if (auth()->check()) {
                // Get all unread notifications
                $unreadNotifications = auth()->user()->unreadNotifications;

                // Pass the entire unread notifications collection to the view
                $view->with('unreadNotifications', $unreadNotifications);
            } else {
                // If not logged in, pass an empty collection
                $view->with('unreadNotifications', collect());
            }
        });

        
    }
}
