<?php

namespace App\Http\Controllers\API\Group;

use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupKickRequest;
use App\Http\Requests\Group\GroupStoreRequest;
use App\Http\Requests\Group\GroupUpdateImageRequest;
use App\Http\Requests\Group\GroupUpdateRequest;
use App\Http\Resources\Group\GroupImageResource;
use App\Http\Resources\Group\GroupResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct(
        private readonly GroupUseCaseInterface $groupUseCase
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $groups = $this->groupUseCase->findAll(Auth::id());

        return GroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupStoreRequest $request): GroupResource
    {
        $data = $request->validated();
        $fileDTO = $request->getFileDTO();

        $group = $this->groupUseCase->create(
            Auth::id(),
            $data['name'],
            $fileDTO,
        );

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): GroupResource
    {
        $group = $this->groupUseCase->findById(Auth::id(), $id);

        return new GroupResource($group);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupUpdateRequest $request, string $id): GroupResource
    {
        $data = $request->validated();
        $fileDTO = $request->getFileDTO();

        $group = $this->groupUseCase->update($id, $data, $fileDTO);

        return new GroupResource($group);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $this->groupUseCase->delete($id);

        return response()->json(null, 204);
    }

    public function leave(string $id): JsonResponse
    {
        $this->groupUseCase->leave(Auth::id(), $id);

        return response()->json(null, 204);
    }

    public function kick(GroupKickRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->groupUseCase->kick($data['userId'], $data['groupId']);

        return response()->json(null, 204);
    }

    public function showImage(string $id): GroupImageResource
    {
        $image = $this->groupUseCase->showImage($id);

        return new GroupImageResource($image);
    }

    public function updateImage(GroupUpdateImageRequest $request, string $id): GroupImageResource
    {
        $request->validated();
        $fileDTO = $request->getFileDTO();

        $group = $this->groupUseCase->uploadImage($id, $fileDTO);

        return new GroupImageResource($group);
    }

    public function destroyImage(string $id): JsonResponse
    {
        $this->groupUseCase->destroyImage($id);

        return response()->json(null, 204);
    }
}
