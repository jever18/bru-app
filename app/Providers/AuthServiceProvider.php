<?php

namespace App\Providers;

// Impor Model dan Policy yang dibutuhkan
use App\Models\Run; 
use App\Policies\RunPolicy; 

// ... (Use statements lainnya)

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan model Run ke RunPolicy
        Run::class => RunPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
