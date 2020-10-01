<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\TagsRequest;
use App\Models\Brand;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        return view('dashboard.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('dashboard.tags.create');
    }


    public function store(TagsRequest $request)
    {


        $tag = Tag::create(['slug' => $request -> slug]);

        //save translations
        $tag->name = $request->name;
        $tag->save();
        DB::commit();
        return redirect()->route('admin.tags')->with(['success' =>__('admin/tags.addSuccess')]);



    }


    public function edit($id)
    {

        //get specific categories and its translations
        $tag = Tag::find($id);

        if (!$tag)
            return redirect()->route('admin.tags')->with(['error' => __('admin/tags.errorNotFind')]);

        return view('dashboard.tags.edit', compact('tag'));

    }


    public function update($id, TagsRequest $request)
    {
        try {
            //validation

            //update DB


            $tag = Tag::find($id);

            if (!$tag)
                return redirect()->route('admin.tags')->with(['error' => __('admin/tags.errorNotFind')]);


            DB::beginTransaction();
            if ($request->has('photo')) {
                $fileName =  $this->saveImage($request->photo, 'assets/images/brands');
                Brand::where('id', $id)
                    ->update([
                        'photo' => $fileName,
                    ]);
            }

            if (!$request->has('is_active'))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);

            $tag->update($request->except('_token', 'id', 'photo'));

            //save translations
            $tag->name = $request->name;
            $tag->save();

            DB::commit();
            return redirect()->route('admin.tags')->with(['success' => __('admin/tags.addSuccess')]);

        } catch (\Exception $ex) {

            DB::rollback();
            return redirect()->route('admin.tags')->with(['error' => __('admin/tags.error')]);
        }

    }


    public function destroy($id)
    {
        try {
            //get specific categories and its translations
            $tag = Tag::find($id);

            if (!$tag)
                return redirect()->route('admin.tags')->with(['error' => __('admin/tags.errorNotFind')]);

            $tag->delete();

            return redirect()->route('admin.tags')->with(['success' => __('admin/tags.deleteSuccess')]);

        } catch (\Exception $ex) {
            return redirect()->route('admin.brands')->with(['error' => __('admin/tags.error')]);
        }
    }

    protected function saveImage($photo, $folder)
    {
        $file_extension = $photo->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;
        $path = $folder;
        $photo->move($path, $file_name);
        return $file_name;

    }


}
