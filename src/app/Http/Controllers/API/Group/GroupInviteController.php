<?php

namespace App\Http\Controllers\API\Group;

use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupInviteController extends Controller
{
    public function __construct(
        private readonly GroupUseCaseInterface $groupUseCase,
    ) {}

    public function getInviteLink(string $groupId)
    {
        $token = $this->groupUseCase->getInviteLink(Auth::id(), $groupId);

        return response()->json(['token' => $token]);
    }

    public function invite(Request $request)
    {
        $queryToken = $request->query('token', 'null');

        $this->groupUseCase->invite(Auth::id(), $queryToken);

        return response()->json(['message' => 'User add to group!']);
    }
}
