<?php

namespace App\Providers;

use App\Models\Channel;
use App\Models\Video;
use App\Policies\ChannelPolicy;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Le policy dell'applicazione.
     */
    protected $policies = [
        Channel::class => ChannelPolicy::class,
        Video::class => VideoPolicy::class,
    ];

    /**
     * Bootstrap per i servizi di autenticazione/authorizzazione.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
