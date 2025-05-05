<?php

namespace App\Http\Controllers\API;

use App\Domain\UseCase\ShoppingList\ShoppingListUseCaseInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\List\ShoppingListIndexRequest;
use App\Http\Requests\List\ShoppingListStoreRequest;
use App\Http\Requests\List\ShoppingListUpdateRequest;
use App\Http\Resources\ShoppingListResource;
use Illuminate\Support\Facades\Auth;

class ShoppingListController extends Controller
{
    public function __construct(
        private readonly ShoppingListUseCaseInterface $shoppingListUseCase,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(ShoppingListIndexRequest $request)
    {
        $groupId = $request->validated()['group_id'];

        $shoppingLists = $this->shoppingListUseCase->findAll(Auth::id(), $groupId);

        return ShoppingListResource::collection($shoppingLists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShoppingListStoreRequest $request)
    {
        $data = $request->validated();

        $shoppingList = $this->shoppingListUseCase->create(
            Auth::id(),
            $data['name'],
            $data['groupId'],
            $data['hidden']
        );

        return new ShoppingListResource($shoppingList);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shoppingList = $this->shoppingListUseCase->findById($id);

        return new ShoppingListResource($shoppingList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShoppingListUpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $shoppingList = $this->shoppingListUseCase->update($id, $data);

        return new ShoppingListResource($shoppingList);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->shoppingListUseCase->delete($id);

        return response()->json(null, 204);
    }
}
