<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\UppercaseName;
use App\Http\Middleware\LogRequests;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route:: get("/test", function () {
    return "API is working!";
});

Route:: get("/backend-test", [
    BackendController::class, 'getAll'
    ])->middleware("check.header:12345,someValue");

Route:: get("/backend-test/{id?}", [
    BackendController::class, 'get'
    ]);

 Route:: post("/backend-test", [
    BackendController::class, 'create'
    ]);   

 Route:: put("/backend-test/{id}", [  
    BackendController::class, 'update'
    ]);   
 
 Route:: delete("/backend-test/{id}", [  
    BackendController::class, 'delete'
    ]);   

Route:: get("/query-test", [
    QueryController::class, 'get'
    ]);    

Route:: get("/query-test/{id?}", [
    QueryController::class, 'getById'
    ]);

Route:: get("/query-test/method/names", [
    QueryController::class, 'getNames'
    ]);    
Route:: get("/query-test/method/search/{name}/{price}", [
    QueryController::class, 'getSearch'
    ]);    

Route:: post("/query-test/method/advancedSearch", [
    QueryController::class, 'advancedSearch'
    ]); 

Route:: get("/query-test/method/join", [
    QueryController::class, 'join'
    ]) -> middleware([
        UppercaseName::class,
         CheckValueInHeader::class.':12345,someValue'
         ]);

Route::apiResource('/product', ProductController::class)
->middleware(["jwt.auth", LogRequests::class]);   

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login'])->name('login');

Route::middleware('jwt.auth')->group(function () {
    Route::get("/who", [AuthController::class, 'who']);
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::post("/refresh", [AuthController::class, 'refresh']);
});

Route::get("/info/tax/{id}", [InfoController::class, 'iva']);
Route::get("/info/encrypt/{data}", [InfoController::class, 'encrypt']);
Route::get("/info/decrypt/{data}", [InfoController::class, 'decrypt']);
Route::get("/info/encrypt-user-email/{id}", [InfoController::class, 'encryptUserEmail']);
Route::get("/info/singleton", [InfoController::class, 'singletonValue']);
Route::get("/info/encrypt-user-email2/{id}", [InfoController::class, 'encryptUserEmail2']);