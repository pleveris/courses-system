<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FolderRequest;
use App\Folder;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Folder::with('files')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Return all folders that belong to a specific course
     *
     * @param  int  $course_id
     * @return \Illuminate\Http\Response
     */

    public function courseFolders($course_id)
    {
        return Folder::with('files')->orderBy('created_at', 'asc')
            ->where('course_id', $course_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FolderRequest $request)
    {
        $data = [];
        $data['course_id'] = $request->course_id;
        $data['user_id'] = $request->user_id;
        $data['name'] = $request->name;
        try {
            $folder = Folder::create($data);
            return response()->json($folder, 201);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $folder = Folder::with('files')->find($id);
        return response()->json($folder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FolderRequest $request, $id)
    {
        try {
            $folder = Folder::find($id);
            $folder->name = $request->name;
            $folder->save();

            return response()->json($folder, 200);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(null, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $folder = Folder::find($id);
        $folder->delete();

        return response()->json(null, 204);
    }
}
