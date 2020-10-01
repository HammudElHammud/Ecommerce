<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::with(['_parent'])->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories =Category::select('id','parent_id')->get();
        return view('dashboard.categories.create' ,compact('categories'));
    }


    public function store( Request $request)
    {
//        return  $request;


//        try {

//            DB::beginTransaction();

            //validation
            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            if($request -> type == 1) //main category
             {
                   $request->request->add(['parent_id' => null]);
              }


        $category = Category::create([
                'slug'=> $request->slug,
                'is_active' =>$request->is_active,
                'parent_id' =>$request->parent_id,

            ]);

//            $category = Category::create($request->except('_token'));

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' => __('admin/main.addSuccess')]);
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
            return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.errorNotFind')]);

        return view('dashboard.categories.edit', compact('category'));

    }


    public function update($id, MainCategoryRequest $request)
    {
        try {
            //validation

            //update DB


            $category = Category::find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.errorNotFind')]);

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $category->update($request->all());

            //save translations
            $category->name = $request->name;
            $category->save();

            return redirect()->route('admin.maincategories')->with(['success' =>__('admin/main.updateSuccess')]);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.error')]);
        }

    }


    public function destroy($id)
    {

        try {
            //get specific categories and its translations
             $category = Category::orderBy('id', 'DESC')->find($id);

            if (!$category)
                return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.errorNotFind')]);

            $category->delete();

            return redirect()->route('admin.maincategories')->with(['success' => __('admin/main.deleteSuccess')]);

        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => __('admin/main.error')]);
        }
    }

}
