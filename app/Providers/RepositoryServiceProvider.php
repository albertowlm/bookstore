<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\BookRepository;
use App\Repositories\BookRepositoryEloquent;


use App\Repositories\AuthorRepository;
use App\Repositories\AuthorRepositoryEloquent;

use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryEloquent;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            BookRepository::class,
            BookRepositoryEloquent::class
        );
        $this->app->bind(
            AuthorRepository::class,
            AuthorRepositoryEloquent::class
        );
        $this->app->bind(
            SubjectRepository::class,
            SubjectRepositoryEloquent::class
        );
    }
}
