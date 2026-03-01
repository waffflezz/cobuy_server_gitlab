<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\DomainServicesServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\UseCaseServiceProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
];
