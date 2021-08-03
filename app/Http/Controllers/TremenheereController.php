<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\TremenheereStock;
use App\Models\ProductVariants;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TremenheereController extends Controller
{


    public function viewcategory(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->id;
        $categories = Categories::find($id);
        $subcategories = Categories::where('parent_id', $request->id)->get();
        
        $products = TremenheereStock::with('variants')->where('parent_id', $id)->get();

        return compact('id', 'subcategories', 'products');
    }

    public function miscellaneous()
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = TremenheereStock::whereNull('parent_id')->with('variants')->get();
        return compact('products');
    }

    public function varianttocart(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->product_id;
        $variantid = $request->variant_id;
        if(!$request->variant_id){
            abort(404);
        }
        $products = TremenheereStock::where('id', $request->product_id)->first();
        $product = ProductVariants::where('id', $request->variant_id)->first();

        if(!$product) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty then this the first product
        if(!$cart) {
            $cart = [
                    $id => [
                        $variantid => [
                            "id" => $product->id,
                            "name" => $products->name,
                            "variant" => $product->name,
                            "productid" => $product->product_id,
                            "quantity" => 1,
                            "price" => $product->price,
                            "photo" => $product->img_path
                        ]
                    ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added.');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id][$variantid])) {
            $cart[$id][$variantid]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added.');
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id][$variantid] = [
            "id" => $product->id,
            "name" => $products->name,
            "variant" => $product->name,
            "productid" => $product->product_id,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->img_path
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added.');

    }
    public function addtocart(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->product_id;
        if(!$id){
            abort(404);
        }
        $product = TremenheereStock::where('id', $request->product_id)->first();

        if(!$product) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty then this the first product
        if(!$cart) {
            $cart = [
                    $id => [
                        0 => [
                            "id" => $product->id,
                            "name" => $product->name,
                            "variant" => "",
                            "productid" => 0,
                            "quantity" => 1,
                            "price" => $product->price,
                            "photo" => $product->img_path
                        ]
                    ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added.');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id][0])) {
            $cart[$id][0]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added.');
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id][0] = [
            "id" => $product->id,
            "name" => $product->name,
            "variant" => "",
            "productid" => 0,
            "quantity" => 1,
            "price" => $product->price,
            "photo" => $product->img_path
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added.');

    }



}
