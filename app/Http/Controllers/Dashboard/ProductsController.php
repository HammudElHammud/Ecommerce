<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralProductRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::select('id','slug','price', 'created_at')->paginate(PAGINATION_COUNT);
        return view('dashboard.products.general.index', compact('products'));
    }

    public function create()
    {
        $data=[];
        $data['brands']  = Brand::where('is_active',1)->get();
        $data['tags']  = Tag::all();
        $data['categories']  = Category::where('is_active',1) ->get();


        return view('dashboard.products.general.create' ,$data);
    }


    public function store(GeneralProductRequest $request)
    {
//        return  $request;


//        try {

           DB::beginTransaction();

        //validation

        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $product = Product::create([
            'slug' => $request->slug,
            'brand_id' => $request->brand_id,
            'is_active' => $request->is_active,
        ]);
        //save translations
        $product->name = $request->name;
        $product->description = $request->description;
        $product->short_description = $request->short_description;
        $product->save();

        //save product categories

        $product->categories()->attach($request->categories);

        //save product tags

        DB::commit();

        return redirect()->route('admin.products')->with(['success' => __('admin/main.addSuccess')]);
    //            DB::commit();

//        } catch (\Exception $ex) {
//            DB::rollback();
//            return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.error')]);
//        }

    }


    public function edit($id)
    {

        //get specific categories and its translations
        $category = Category::orderBy('id', 'DESC')->find($id);

        if (!$category)
            return redirect()->route('admin.products')->with(['error' => __('admin/main.errorNotFind')]);

        return view('dashboard.products.edit', compact('category'));

    }


    public function update($id, MainCategoryRequest $request)
    {
        try {
            //validation

            //update DB


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.products')->with(['error' => __('admin/main.errorNotFind')]);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.products')->with(['success' =>__('admin/main.updateSuccess')]);
        } catch (\Exception $ex) {

            return redirect()->route('admin.products')->with(['error' => __('admin/main.error')]);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
             $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('admin.products')->with(['error' => __('admin/main.errorNotFind')]);

            $category->delete();

            return redirect()->route('admin.products')->with(['success' => __('admin/main.deleteSuccess')]);

        } catch (\Exception $ex) {
            return redirect()->route('admin.products')->with(['error' => __('admin/main.error')]);
        }
    }

}
