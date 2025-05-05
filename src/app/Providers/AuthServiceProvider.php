<?php

namespace App\Providers;

use App\Domain\DTO\Policy\GroupIdDTO;
use App\Models\UserModel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('groupMember', function (UserModel $user, GroupIdDTO $groupIdDTO) {
            return $user->groups->contains($groupIdDTO->id);
        });

        Gate::define('groupOwner', function (UserModel $user, GroupIdDTO $groupIdDTO) {
            return $user->groups->contains(function ($userGroup) use ($groupIdDTO, $user) {
                return $userGroup->id === $groupIdDTO->id && $userGroup->owner_id === $user->id;
            });
        });
    }
}
