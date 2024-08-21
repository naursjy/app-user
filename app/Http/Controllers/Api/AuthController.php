<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\M_artikel;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RolesResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'data' => $validator->errors()
            ]);

            // return response()->json(['error' => $validator->messages()], 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['email'] = $user->email;
        $success['name'] = $user->name;

        // $artikel = new M_artikel;
        // $artikel->judul_artikel = 'perkembangan daya ingat';
        // $artikel->isi_artikel = 'akankah ini berhasil';
        // $artikel->tanggal = date('Y-m-d', strtotime($request->tanggal));
        // $artikel->user_id = $user->id;
        return response()->json([
            'success' => true,
            'message' => 'Register Berhasil',
            'data' => $success,
            // $artikel
        ]);
    }

    // public function register(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|confirmed',
    //         'password_confirmation' => 'required',
    //     ]);

    //     $user = User::create([
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'password' => Hash::make($validatedData['password']),
    //     ]);

    //     // Associate an article with the newly created user
    //     $article = new M_artikel();
    //     $article->judul_artikel = $request->judul_artikel;
    //     $article->user_id = $user->id;
    //     $article->save();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Register Berhasil',
    //         'data' => $user
    //     ]);
    // }

    public function Login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek kembali email dan password',
                'data' => null
            ]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return new RolesResource(true, 'logout Berhasil', $request);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
}
