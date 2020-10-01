<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::Child()->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);

        return view('dashboard.subcategories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::parent()->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);


        return view('dashboard.subcategories.create' ,compact('categories'));
    }


    public function store(MainCategoryRequest $request)
    {


//        try {

//            DB::beginTransaction();

        //validation
        if (!$request->has('is_active'))
            $request->request->add(['is_active' => 0]);
        else
            $request->request->add(['is_active' => 1]);

        $category = Category::create([
            'slug'=> $request->slug,
            'is_active' =>$request->is_active,

        ]);

//            $category = Category::create($request->except('_token'));

        //save translations
        $category->name = $request->name;
        $category->save();

        return redirect()->route('admin.subcategories')->with(['success' => __('admin/main.addSuccess')]);
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
            return redirect()->route('admin.subcategories')->with(['error' => __('admin/main.errorNotFind')]);

        return view('dashboard.subcategories.edit', compact('category'));

    }


    public function update($id, MainCategoryRequest $request)
    {
        try {
            //validation

            //update DB


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.subcategories')->with(['error' => __('admin/main.errorNotFind')]);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.subcategories')->with(['success' =>__('admin/main.addSuccess')]);
        } catch (\Exception $ex) {

            return redirect()->route('admin.subcategories')->with(['error' => __('admin/main.error')]);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
            $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('admin.subcategories')->with(['error' => __('admin/main.errorNotFind')]);

            $category->delete();

            return redirect()->route('admin.subcategories')->with(['success' => __('admin/main.deleteSuccess')]);

        } catch (\Exception $ex) {
            return redirect()->route('admin.subcategories')->with(['error' => __('admin/main.error')]);
        }
    }

}
