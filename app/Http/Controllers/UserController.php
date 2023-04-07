<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userQuery = User::query()->whereRole('user');
        if($request->ajax()){
            return DataTables::of($userQuery)
                ->addIndexColumn()
                 ->addColumn('action', function($row) use ($user){
                    return "<button class='btn btn-sm btn-info' onclick=updateUser('".$row->id."')>Edit</button>";   
                }) 
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('users.index');
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $fillData = $request->only(['name','email']);
            if(!empty($request->password)){
                $fillData['password'] = bcrypt($request->password);
            }
            $user->update($fillData);
            return response()->json([
                'status' => true,
                'message' => "User Found",
                'data' => $user
            ]);
      
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function show(Request $request){
        try {
            $user = User::findOrFail($request->id);
            return response()->json([
                'status' => true,
                'message' => "User Found",
                'data' => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
}
