<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function reviewOrder(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cart = session()->get('cart');
        
        return view('tremenheere.pos.checkout', compact('cart'));

    }

    public function placeOrder(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cart = session()->get('cart');
        if(isset($cart)){
            $total = 0;
            foreach($cart as $items){
                foreach($items as $item){
                    $total += $item['price'];
                }
            }   
            $cart = session()->get('cart');
            $order = new Order([
                'creator' => Auth::user()->name,
                'total' => $total,
                'notes' => $request->notes,
                'email' => $request->email,
                'handled' => $request->handled,
                'phone' => $request->phone
            ]);
            $order->save();
            $order->paymentDetails()->create([
                'payment_method' => $request->payment_method
            ]);


            $orderItems = [];
            foreach($cart as $items){
                foreach($items as $item){
                    $orderItems[] = [
                        'order_id' => $order->id,
                        'tremenheere_stock_id' => $item['id'],
                        'product_variant_id' => $item['productid'],
                        'quantity' => $item['quantity']
                    ];
                }
                
            };
            $order->items()->attach($orderItems); 
            $request->session()->forget('cart');
            return redirect()->route('tremenheereposindex')->with('success', 'Order complete!');
            
            
        }
    }
}
