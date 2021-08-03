<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TremenheereStock;
use App\Models\Categories;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
class TremenheerePOSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $categories = Categories::where('parent_id', 0)->orderBy('name', 'ASC')->get();
        if($request->ajax()){
            if($request->id){
                $category = Categories::where('id', $request->id)->with('children')->with('parent')->with('tremenheerestock')->first();
            }else{
                $category = Categories::where('parent_id', 0)->orderBy('name', 'ASC')->get();
            }
            return $category;
        }
 
        return view('tremenheere.pos.index', compact('categories'));
    }

        
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request['id'] and $request['quantity'])
        {
            if($request['productid'] != 0){
                $cart = session()->get('cart');
                $id = $cart[$request['productid']][$request['id']];
                $quantity = $id['quantity'];
                $cart[$request['productid']][$request['id']]['quantity'] = $request['quantity'];
                session()->put('cart', $cart);
                return session('success', 'Cart updated!');
            }else{
                $cart = session()->get('cart');
                $id = $cart[$request['id']][$request['productid']];
                $quantity = $id['quantity'];
                $cart[$request['id']][$request['productid']]['quantity'] = $request['quantity'];
                session()->put('cart', $cart);
                return session('success', 'Cart updated!');
            }
        }
        return redirect()->back()->with('error', 'Cart failed to update.');
        return false;
    }
    public function remove(Request $request)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        if(!$request->productid) {
            $cart = session()->get('cart');
            if(isset($cart[$request['id']])) {
                unset($cart[$request['id']]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Product removed from cart.');
        }
        if($request->productid) {
            $cart = session()->get('cart');
            if(isset($cart[$request['productid']][$request['id']])) {
                unset($cart[$request['productid']][$request['id']]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Product removed from cart.');
        }

        return redirect()->back()->with('error', 'Cart failed to update.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
