<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

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
    public function boot(): void
    {
        // Share unread message count with all views for authenticated users
        View::composer('*', function ($view) {
            if (Auth::guard('web')->check()) {
                $unreadCount = Message::where('receiver_id', Auth::guard('web')->id())
                    ->where('is_read', false)
                    ->count();
                $view->with('unreadMessageCount', $unreadCount);
            } else {
                $view->with('unreadMessageCount', 0);
            }
        });
    }
}
