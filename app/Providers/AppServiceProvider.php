<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Post;
use Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);//https://laravel-news.com/laravel-5-4-key-too-long-error


        Post::created(function ($post) {
            echo 'c';
        $f=    Mail::raw('Text', function ($message){
                $message->from('admin@localhost.com')->to('tania.pets@gmail.com')->subject('New Post Added');;
            });

            dd($f);

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
