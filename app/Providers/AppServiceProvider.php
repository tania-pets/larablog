<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Post;
use Mail;
use Config;
use App\Mail\EmailNewPostNotify;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping(); //Do not wrap json responses inside "Data" wrapper

        Schema::defaultStringLength(191);//https://laravel-news.com/laravel-5-4-key-too-long-error

        //send email to admin after post creation - Queue it to avoid delay
        Post::created(function ($post) {
            $mailData = ['link' => url('api/posts/' . $post->id), 'title' => $post->title];
            Mail::to(Config::get('app.admin_mail'))->queue(new EmailNewPostNotify($mailData));
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
