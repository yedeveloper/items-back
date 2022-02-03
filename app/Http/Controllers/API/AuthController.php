<?php
namespace App\Http\Controllers\API;

error_reporting(E_ALL);

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Welcome to the system','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You are now out of the system'
        ];
    }

    //Get All items
    public function getAll(){

        $users = DB::table('items')
            ->select('nombre')
            ->get();
        
            return $users;

    }

    //Update list item from frontend
    public function syncAll(Request $request){

        $arrObj = $request->items;
        $arrIn = array();

        DB::table('items')->truncate();

        for ($k=0; $k < count($arrObj); $k++) { 
            $arrIn[] = array('nombre' => $arrObj[$k]['nombre']);
        }

        DB::table('items')->insert($arrIn);

        return [
            'message' => 'Items in server'
        ];

    }

    //Create a new item
    public function newItem(Request $request){

        $arrObj = $request->item;
        $arrIn = (array) $arrObj[0];

        DB::table('items')->insert($arrIn);

        return [
            'message' => 'Item in server'
        ];

    }

    //Update an existing item
    public function updateItem(Request $request){

        $arrIn = $request->item;
        $antNombre = $arrIn[0];
        $new = (array) $arrIn[1];

        $affected = DB::table('items')
              ->where('nombre', $antNombre)
              ->update($new);

        return [
            'message' => 'Updated item in server'
        ];

    }

    //Delete an existing item
    public function deleteItem(Request $request){

        $antNombre = $request->item;

        $deleted = DB::table('items')->where('nombre', '=', $antNombre)->delete();

        return [
            'message' => 'Deleted item in server'
        ];

    }

}