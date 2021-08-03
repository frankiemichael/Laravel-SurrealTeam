<?php

namespace App\Http\Controllers;
use App\Models\TremenheereStock;
use App\Models\Categories;
use App\Models\ProductVariants;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use PDF;
use SnappyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Label;
use Illuminate\Http\Request;
use App\Models\LabelRequest;
class LabelController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){

            $products = TremenheereStock::with('parent')->orderBy('name', 'ASC')->where('name', 'LIKE', '%'.$request->search.'%')->paginate(20);
            return $products;
        }
        $order = '';
        if ($request->session()->get('order') !== null){
            $order = $request->session()->get('order');
        }

        $column = isset($request['column']) ? ($request['column']) : "id";
        $ascdesc = isset($request['ascdesc']) ? ($request['ascdesc']) : "desc";
        if ($request['ascdesc'] == 'asc'){
            $switch = 'desc';
        }else{
            $switch = 'asc';
        }
        

        $stock = TremenheereStock::with('parent')->orderBy('name', 'ASC')->paginate(20);
        return view('tremenheere.labels.index', compact('switch', 'stock', 'order'));
    }


    public function requests()
    {
        $requests = LabelRequest::with('items')->with('user')->get();
        return view('tremenheere.labels.requests', compact('requests'));

    }
    
    public function requestshow($id)
    {
        $request = LabelRequest::where('id', $id)->with('items')->with('items.product')->with('items.variant')->with('items.variant.variants')->first();
        $image = Storage::get('images/trem-logo.txt');
        $divid = 1;
        
        return view('tremenheere.labels.requestshow', compact('request', 'image', 'divid'));

    }

    public function pdf($id)
    {
        $request = LabelRequest::where('id', $id)->with('items')->with('items.product')->with('items.variant')->with('items.variant.variants')->first();
        $image = Storage::get('images/trem-logo.txt');
        $divid = 1;


        $pdf = PDF::loadHtml('<div>hello</div>')->setOption('disable-smart-shrinking', true)->setOption('enable-smart-shrinking', false)->setOption('page-width', 10)->setOption('page-height', 1.9);
        return $pdf->download();
    }

    public function update(Request $request)
    {
        if($request->variant_id){
            
            $id = $request->product_id;
            $variantid = $request->variant_id;

            $products = TremenheereStock::where('id', $request->product_id)->first();
            $product = ProductVariants::where('id', $request->variant_id)->first();

            if(!$product) {
                abort(404);
            }
            $order = session()->get('order');
            // if cart is empty then this the first product
            if(!$order) {
                $order = [
                        $id => [
                            $variantid => [
                                "id" => $product->id,
                                "name" => $products->name,
                                "variant" => $product->name,
                                "productid" => $product->product_id,
                                "quantity" => $request->quantity,
                                "photo" => $product->img_path
                            ]
                        ]
                ];
                session()->put('order', $order);
                return redirect()->back()->with('success', 'Product added.');
            }

            // if cart not empty then check if this product exist then increment quantity
            if(isset($order[$id][$variantid])) {
                $order[$id][$variantid]['quantity'] += $request->quantity;
                session()->put('order', $order);
                return redirect()->back()->with('success', 'Product added.');
            }

            // if item not exist in cart then add to cart with quantity = 1
            $order[$id][$variantid] = [
                "id" => $product->id,
                "name" => $products->name,
                "variant" => $product->name,
                "productid" => $product->product_id,
                "quantity" => $request->quantity,
                "photo" => $product->img_path
            ];
            session()->put('order', $order);
            return redirect()->back()->with('success', 'Product added.');
        }else{
            $id = $request->product_id;
            $products = TremenheereStock::where('id', $request->product_id)->first();
            
            if(!$products) {
                abort(404);
            }
            $order = session()->get('order');
            // if cart is empty then this the first product
            if(!$order) {
                $order = [
                        $id => [
                            0 => [
                                "id" => $products->id,
                                "name" => $products->name,
                                "variant" => "",
                                "productid" => 0,
                                "quantity" => $request->quantity,
                                "photo" => $products->img_path
                            ]
                        ]
                ];
                
                session()->put('order', $order);
                return redirect()->back()->with('success', 'Product added.');
                            // if cart not empty then check if this product exist then increment quantity
            if(isset($order[$id][0])) {
                $order[$id][0]['quantity'] += $request->quantity;
                session()->put('order', $order);
                return redirect()->back()->with('success', 'Product added.');
            }

            // if item not exist in cart then add to cart with quantity = 1
            $order[$id][0] = [
                "id" => $products->id,
                "name" => $products->name,
                "variant" => "",
                "productid" => 0,
                "quantity" => $request->quantity,
                "photo" => $products->img_path
            ];
            session()->put('order', $order);
            return redirect()->back()->with('success', 'Product added.');

            }

            // if cart not empty then check if this product exist then increment quantity
            if(isset($order[$id][0])) {
                $order[$id][0]['quantity'] += $request->quantity;
                session()->put('order', $order);
                return redirect()->back()->with('success', 'Product added.');
            }

            // if item not exist in cart then add to cart with quantity = 1
            $order[$id][0] = [
                "id" => $products->id,
                "name" => $products->name,
                "variant" => "",
                "productid" => 0,
                "quantity" => $request->quantity,
                "photo" => $products->img_path
            ];
            session()->put('order', $order);
            return redirect()->back()->with('success', 'Product added.');

        }
    }

    public function delete(Request $request)
    {
        if(!$request->variant_id) {
            $order = session()->get('order');
            if(isset($order[$request['id']])) {
                unset($order[$request['id']]);
                session()->put('order', $order);
            }
            return redirect()->back()->with('success', 'Product removed from order.');
        }
        if($request->variant_id) {
            $order = session()->get('order');
            if(isset($order[$request['productid']][$request['id']])) {
                unset($order[$request['productid']][$request['id']]);
                session()->put('order', $order);
            }
            return redirect()->back()->with('success', 'Product removed from order.');
        }

        return redirect()->back()->with('error', 'Order failed to update.');

    }

    public function completerequest($id)
    {
        $request = LabelRequest::where('id', $id);
        $request->delete();
        return back()->with('success', 'Request deleted.');
    }

    public function orderupdate(Request $request)
    {
        if($request['id'] and $request['quantity'])
        {
            if($request['productid'] != 0){
                $order = session()->get('order');
                $id = $order[$request['productid']][$request['id']];
                $quantity = $id['quantity'];
                $order[$request['productid']][$request['id']]['quantity'] = $request['quantity'];
                session()->put('order', $order);
                return session('success', 'Order updated!');
            }else{
                $order = session()->get('order');
                $id = $order[$request['id']][0];
                $quantity = $id['quantity'];
                $order[$request['id']][$request['productid']]['quantity'] = $request['quantity'];
                session()->put('order', $order);
                return session('success', 'Order updated!');
            }
        }
        return redirect()->back()->with('error', 'Order failed to update.');
        return false;
    }

    public function completeorder(Request $request)
    {
        $order = session()->get('order');

        $labelrequest = new LabelRequest([
            'user_id' => Auth::id(),
            'deadline' => $request->deadline,
        ]);
        $labelrequest->save();
        foreach($order as $item){
            foreach($item as $label){
                $newlabel = new Label([
                    'order_id' => $labelrequest->id,
                    'product_id' => $label['id'],
                    'variant_id' => $label['productid'],
                    'quantity' => $label['quantity'],
                ]);
                $newlabel->save();
                $stock = TremenheereStock::where('id', $label['id'])->first();
                
                if($stock->stock > 0){
                    $stock->stock -= $label['quantity'];
                    $stock->save();
                }
                
            }
        }
        session()->forget('order');
        return redirect()->route('tremenheere.labels.index')->with('success', 'Label request successfully sent.');
    }

}
