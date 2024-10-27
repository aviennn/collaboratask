<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Share unread notifications with the navigation view
        View::composer('layouts.navigation', function ($view) {
            $unreadNotifications = auth()->user()->unreadNotifications;  // Fetch unread notifications
            $view->with('unreadNotifications', $unreadNotifications);    // Pass to the view
        });
    }
}
