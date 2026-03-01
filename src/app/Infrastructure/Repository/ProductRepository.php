<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Product;
use App\Domain\Exceptions\ProductNotFoundException;
use App\Domain\Exceptions\ShoppingListNotFoundException;
use App\Domain\UseCase\Product\ProductRepositoryInterface;
use App\Models\ProductModel;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @throws ShoppingListNotFoundException
     */
    public function findAll(int $shoppingListId): array
    {
        $products = ProductModel::where('shopping_list_id', $shoppingListId)->latest()->get();

        return $products->map(fn (ProductModel $productModel) => $productModel->toDomain())->all();
    }

    public function create(array $data, ?string $image): Product
    {
        /** @var ProductModel $product */
        $product = ProductModel::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'status' => $data['status'],
            'shopping_list_id' => $data['shopping_list_id'],
            'image' => $image,
            'price' => $data['price'],
            'count' => $data['count'],
        ]);

        return $product->toDomain();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function findById(int $productId): Product
    {
        $product = ProductModel::find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Product with id ' . $productId . ' not found', 404);
        }

        return $product->toDomain();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function update(int $productId, array $data): Product
    {
        $product = ProductModel::find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Product with id ' . $productId . ' not found', 404);
        }

        $product->update($data);

        return $product->toDomain();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function destroy(int $productId): Product
    {
        $product = ProductModel::find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Product with id ' . $productId . ' not found', 404);
        }

        $product->delete();

        return $product->toDomain();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function uploadImage(int $productId, string $image): Product
    {
        $product = ProductModel::find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Product with id ' . $productId . ' not found', 404);
        }

        $product->update(['image' => $image]);

        return $product->toDomain();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function destroyImage(int $productId): Product
    {
        $product = ProductModel::find($productId);

        if (!$product) {
            throw new ProductNotFoundException('Product with id ' . $productId . ' not found', 404);
        }

        $product->image = null;
        $product->save();

        return $product->toDomain();
    }
}
