<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Resources\BeritaResource;
use App\Models\M_Roles;
use GuzzleHttp\Middleware;
use Monolog\Handler\RollbarHandler;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'Register']);
Route::post('login', [AuthController::class, 'Login']);
Route::post('logout', [AuthController::class, 'Logout'])->middleware('auth:sanctum');


// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('/admin-only', [BeritaController::class, 'DataView'])->middleware('roles:superadmin,admin');
//     Route::post('/superadmin-only', [BeritaController::class, 'InsertBerita'])->middleware('roles:superadmin');
// });

// Route::get('login', function () {
//     $user = User::find(1);
//     $role = M_Roles::find(1);
//     if ($user && $role) {
//         $user->roles()->attach($role->id);
//         return response()->json(['message' => 'Role attached successfully']);
//     } else {
//         return response()->json(['message' => 'User or role not found'], 404);
//     }
// });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users', [AuthController::class, 'index']);

Route::get('/artikel', [ArtikelController::class, 'DataArtikel']);
// ->middleware('auth:sanctum');

Route::post('/insert-artikel', [ArtikelController::class, 'InsertArtikel'])
    ->middleware('auth:sanctum');

Route::get('/deleted-artikel/{id}', [ArtikelController::class, 'DeletedArtikel'])
    ->middleware('auth:sanctum');
Route::get('/detail-artikel', [ArtikelController::class, 'DetailArtikel'])
    ->middleware('auth:sanctum');
Route::post('/update-artikel/{id}', [ArtikelController::class, 'UpdateArtikel'])
    ->middleware('auth:sanctum');

//rotue Berita
Route::get('/berita', [BeritaController::class, 'DataView']);
Route::post('/insert-berita', [BeritaController::class, 'InsertBerita'])
    ->middleware('auth:sanctum', 'roles:superadmin');
Route::get('/detail-artikel/{id}', [BeritaController::class, 'Detail_Berita'])
    ->middleware('auth:sanctum');
Route::post('/update-berita/{id}', [BeritaController::class, 'UpdateBerita'])
    ->middleware('auth:sanctum', 'roles:superadmin');
Route::get('/deleted-berita/{id}', [BeritaController::class, 'DeleteBerita'])
    ->middleware('auth:sanctum', 'roles:superadmin');


//Route Roles
Route::get('/roles', [RolesController::class, 'ListRoules']);
Route::post('/insert-roles', [RolesController::class, 'InsertRoules'])
    ->middleware('auth:sanctum');
Route::post('/update-roles/{id}', [RolesController::class, 'UpdateRoules'])
    ->middleware('auth:sanctum');
Route::get('/deleteroles/{id}', [RolesController::class, 'DeleteRoules'])
    ->middleware('auth:sanctum');
