<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TremenheereStock;
use App\Models\Categories;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class TremenheereStockController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $column = isset($request['column']) ? ($request['column']) : "id";
        $ascdesc = isset($request['ascdesc']) ? ($request['ascdesc']) : "desc";
        if ($request['ascdesc'] == 'asc'){
            $switch = 'desc';
        }else{
            $switch = 'asc';
        }
        

        $categories = Categories::where('parent_id', 0)->with('tremenheerestock')->with('tremenheerestock.variants')->with('children')->orderBy('name', 'ASC')->get();
        return view('tremenheere.stock.index', compact('switch', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $none = Categories::where('id', 1)->first();
        $categories = Categories::where('parent_id', 0)->orderBy('name', 'ASC')->with('children')->get();
        $prevurl = url()->previous();
        return view('tremenheere.stock.create', compact('none', 'categories', 'prevurl'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if($request->file('img_path')){
            $imgname = $request->file('img_path')->getClientOriginalName();
            $imgname = str_replace('.png', '', $imgname);
            $imgname = str_replace('.jpg', '', $imgname);
            $img_path = $request->file('img_path')->storeAs('images/products', 'img-' . $imgname . date('his') .  "." .$request->file('img_path')->getClientOriginalExtension());
        }else{
            $img_path = NULL;
        }
        $slug = str_replace(' ', '-',$request->name);
        $slug = str_replace("'", '',$slug);
        $slug = str_replace("`", '', $slug);
        $slug = str_replace("’", '', $slug);
        $slug = str_replace("‘", '', $slug);
        $slug = str_replace("®", '', $slug);

        $slug = strtolower($slug);
        $stock = new TremenheereStock([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'img_path' => $img_path,
            'price' => $request->price,
            'stock' => $request->stock,
            'hardiness_zone' => $request->hardiness_zone,
            'soil_type' => json_encode($request->soil_type),
            'light_aspect' => json_encode($request->light_aspect),
        ]);
        $stock->save();
        if(str_contains($request->prevurl, 'labels')){
            return redirect()->route('tremenheere.labels.index')->with('success', 'Product created successfully.');
        }else{
            return redirect()->route('tremenheere.stock.index')->with('success', 'Product created successfully.');
        }
    }
    
    public function createcategory()
    {
        $parents = Categories::get();
        $prevurl = url()->previous();
        return view('tremenheere.stock.createcategory', compact('parents', 'prevurl'));
    }
    public function editcategory($id)
    {
        $category = Categories::where('id', $id)->first();
        $parents = Categories::get();
        return view('tremenheere.stock.editcategory', compact('category', 'parents'));
    }
    public function categorystore(Request $request)
    {
        $duplicate = Categories::where('name', $request->name)->first();
        if($duplicate){
            if($request->ajax()){
                return $duplicate;
            }
            return redirect()->back()->with('error', 'Category already exists.')->withInput();
        }

        if(!$request->parent_id){
            $parent_id = 0;
        }else{
            $parent_id = $request->parent_id;
        }
        if($request->img_path) {
            $imgname = $request->file('img_path')->getClientOriginalName();
            $imgname = str_replace('.png', '', $imgname);
            $imgname = str_replace('.jpg', '', $imgname);
            $img_path = $request->file('img_path')->storeAs('images/categories', 'img-' . $imgname . date('his') .  "." .$request->file('img_path')->getClientOriginalExtension());
        }else{
            $img_path = NULL;
        }
        
        $category = new Categories([
            'parent_id' => $parent_id,
            'name' => $request->name,
            'img_path' => $img_path,
        ]);
        $category->save();

        if($request->ajax()){
            return $category;
        }else{
            if(str_contains($request->prevurl, 'labels')){
                return redirect()->route('tremenheere.labels.index')->with('success', 'Category created successfully.');
            }else{
                return redirect()->route('tremenheere.stock.index')->with('success', 'Category created successfully.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {   

        $column = isset($request['column']) ? ($request['column']) : "name";
        $ascdesc = isset($request['ascdesc']) ? ($request['ascdesc']) : "desc";
        if ($request['ascdesc'] == 'asc'){
            $switch = 'desc';
        }else{
            $switch = 'asc';
        }
        if($request->column){
            $column = $request->column;
        }else{
            $column = 'name';
        }

        $category = Categories::where('id', $id)->with('tremenheerestock')->with('tremenheerestock.variants')->orderBy($column, $ascdesc)->first();
        return view('tremenheere.stock.show', compact('category', 'switch', 'id'));
    }
    
    public function showproduct($id)
    {
        $categories = Categories::get();
        $product = TremenheereStock::where('id', $id)->first();
        if($product->parent_id > 0){
            $parent = Categories::where('id', $product->parent_id)->first();
        }else{
            $parent = "None";
        }
        return view('tremenheere.stock.showproduct', compact('product', 'categories', 'parent'));
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
    public function update(Request $request, $id)
    {
        if($request->ajax()){
            if($request->type === 'product' && $request->input('stock')){
                $product = TremenheereStock::find($id);
                $product->stock = $request->stock;
                $product->save();
                return session('success', $product->name . ' has been updated.');
    
            }elseif($request->type === 'product' && $request->input('price')){
    
                $product = TremenheereStock::find($id);
                $product->price = $request->price;
                $product->save();
                return session('success', $product->name . ' has been updated.');
    
            }elseif($request->type === 'variant' && $request->input('stock')){
    
            }elseif($request->type === 'variant' && $request->input('price')){
    
            }    
        }else{
            $product = TremenheereStock::where('id', $id)->first();
            if($request->img_path !== NULL){
                Storage::delete($product->img_path);
                $imgname = $request->file('img_path')->getClientOriginalName();
                $imgname = str_replace('.png', '', $imgname);
                $imgname = str_replace('.jpg', '', $imgname);
                $img_path = $request->file('img_path')->storeAs('images/products', 'img-' . $imgname . date('his') . "." . $request->file('img_path')->getClientOriginalExtension());
    
                $product->update([
                    'name' => $request->name,
                    'parent_id' => $request->parent_id,
                    'description' => $request->description,
                    'img_path' => $img_path,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'hardiness_zone' => $request->hardiness_zone
                ]);
                $product->save();
            }else{
                $product->update([
                    'name' => $request->name,
                    'parent_id' => $request->parent_id,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                    'hardiness_zone' => $request->hardiness_zone
                ]);
                $product->save();

            }
            return redirect()->back()->with('success', 'Product updated successfully.');
    
        }
    }

    public function updatecategory(Request $request)
    {
        if($request->parent_id == NULL){
            $parent_id == 0;
        }else{
            $parent_id == $request->parent_id;
        }
        if($request->img_path !== NULL){
            $category = Categories::where('id', $request->id)->first();
            Storage::delete($category->img_path);
            $imgname = $request->file('img_path')->getClientOriginalName();
            $imgname = str_replace('.png', '', $imgname);
            $imgname = str_replace('.jpg', '', $imgname);
            $img_path = $request->file('img_path')->storeAs('images/categories', 'img-' . $imgname . date('his') . "." . $request->file('img_path')->getClientOriginalExtension());

            $category->update([
                'name' => $request->name,
                'parent_id' => $parent_id,
                'img_path' => $img_path
            ]);
        }else{
            $category = Categories::where('id', $request->id)->first();
            $category->update([
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ]);

        }
        return redirect()->back()->with('success', 'Category updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteproduct($id)
    {
        $product = TremenheereStock::where('id', $id);
        $product->delete();
        return redirect()->route('tremenheere.stock.index')->with('success', 'Product successfully deleted.');
    
    }
}
