<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, $userId){
        $user = User::find($userId);
        if($user) {
            return response()->json($user);
        }
        return response()->json(['message' => 'User not found!'], 404);
    }

    public function delete(Request $request, $userId) {
        try {
            $user = User::findOfFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }
        $user->delete();
        return response()->json(['message'=>'User deleted successfully.']);
    }

    public function update(Request $request, $userId) {
        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 403);
        }
    
        $user->update($request->all());
    
        return response()->json(['message'=>'User updated successfully.']);
    }
}
