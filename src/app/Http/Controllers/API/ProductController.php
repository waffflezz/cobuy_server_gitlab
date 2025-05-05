<?php

namespace App\Http\Controllers\API;

use App\Domain\UseCase\Product\ProductUseCaseInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateImageRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\Product\ProductImageResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductUseCaseInterface $productUseCase,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(string $shoppingListId)
    {
        $products = $this->productUseCase->findAll($shoppingListId);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request, string $shoppingListId)
    {
        $data = $request->validated();

        $product = $this->productUseCase->create($shoppingListId, $data);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $shoppingListId, string $id)
    {
        $product = $this->productUseCase->findById($shoppingListId, $id);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $shoppingListId, string $id)
    {
        $data = $request->validated();

        $product = $this->productUseCase->update(Auth::id(), $shoppingListId, $id, $data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $shoppingListId, string $id)
    {
        $this->productUseCase->delete($shoppingListId, $id);

        return response()->json(null, 204);
    }

    public function showImage(string $shoppingListId, string $productId)
    {
        $product = $this->productUseCase->findById($shoppingListId, $productId);

        return new ProductImageResource($product);
    }

    public function updateImage(ProductUpdateImageRequest $request, string $shoppingListId, string $productId)
    {
        $request->validated();
        $file = $request->getFileDTO();

        $product = $this->productUseCase->uploadImage($shoppingListId, $productId, $file);

        return new ProductImageResource($product);
    }

    public function destroyImage(string $shoppingListId, string $productId): JsonResponse
    {
        $this->productUseCase->destroyImage($shoppingListId, $productId);

        return response()->json(null, 204);
    }
}
