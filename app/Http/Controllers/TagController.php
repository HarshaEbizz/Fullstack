<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class TagController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tag_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'code' => 202, 'message' => $validator->errors()->first()], 202);
            }
            $tag = Tag::create([
                'tag_name' => $request->tag_name,
            ]);
            return Response(['status' => true, 'message' => 'Tag added successfully.', 'data' => $tag], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show()
    {
        return Tag::orderBy('id', 'desc')->get();
    }

    public function edit(Tag $tag)
    {
        return response()->json($tag);
    }

    public function update(Request $request, Tag $tag)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tag_name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'code' => 202, 'message' => $validator->errors()->first()], 202);
            }
            $tag->update(['tag_name' => $request->tag_name]);
            return Response(['status' => true, 'message' => 'Tag updated successfully.', 'data' => $tag], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Tag $tag)
    {
        if ($tag->delete()) {
            return response()->json(['success' => true, 'message' => 'Tag has been deleted.', 'data' => []], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong.', 'data' => []], 200);
        }
    }
}