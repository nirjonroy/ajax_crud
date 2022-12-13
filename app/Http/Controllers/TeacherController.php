<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teacher.index');
    }

    // -----------------+all data+-----------------
    public function allData()
    {
        $data = Teacher::orderBy('id', 'DESC')->get();
        return response()->json($data);
    } 
    // -----------------+store+-----------------
    public function storeData(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'institute' => 'required',
        ]);
  
        
        $data = Teacher::insert([
            'name' => $request->name,
            'title' => $request->title,
            'institute' => $request->institute,
        ]);
        return response()->json($data);
    }

    public function editData($id)
    {
        $data = Teacher::findOrFail($id);
        return response()->json($data);
    }
    public function updateData(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'institute' => 'required',
        ]);

        $data = Teacher::findOrFail($id)->update([
            'name' => $request->name,
            'title' => $request->title,
            'institute' => $request->institute,
        ]);
        return response()->json($data);
    }
    public function destroyData(Request $request, $id)
    {
        $data = Teacher::where('id', $id)->delete();
        return response()->json($data);
    }
}
