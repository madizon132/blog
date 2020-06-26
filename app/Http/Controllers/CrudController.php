<?php

namespace App\Http\Controllers;

use App\blog as Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\FileUpload;

class CrudController extends Controller
{
    public function index()
    {
        return response(Blog::withTrashed()->get()->jsonSerialize(), Response::HTTP_OK);
    }

    public function show($id)
    {
        return response(Blog::withTrashed()->find($id), Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $blog = new Blog();
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->image = $request->input('image');
        $blog->save();

        return response($blog->jsonSerialize(), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->image = $request->input('image');
        $blog->save();

        return response(null, Response::HTTP_OK);
    }

    public function edit($id)
    {
        $blog = Blog::onlyTrashed()->find($id);
        $blog->restore();

        return response(null, Response::HTTP_OK);
    }


    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();

        return response($blog->jsonSerialize(), Response::HTTP_OK);
    }
}
