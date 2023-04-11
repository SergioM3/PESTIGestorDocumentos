<?php

use App\InterfaceAdapters\Controllers\DocumentTypeController;
use App\InterfaceAdapters\Controllers\TemporaryFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/document_types',[DocumentTypeController::class,'getAllDocumentTypes']); // Get All Motoristas
/*Route::get('/clientes',[ClientesController::class,'getAllClientes']);       // Get All Clientes
Route::get('/camioes',[CamioesController::class,'getAllCamioes']);          // Get All Camioes
Route::get('/viagens',[ViagensController::class,'getAllViagens']);          // Get All Viagens
Route::get('/viagens/tempoViagem/{viagem}',[ViagensController::class,'getTempoEmViagem']);          // Get Tempo total em viagem
Route::get('/viagens/{viagem}/tempoParagem',[ViagensController::class,'getTempoParagem']);          // Get Tempo total em paragem
Route::get('/paragens',[ParagensController::class,'getAllParagens']);       // Get All Paragens
Route::get('/users',[UserController::class,'getAllUsers']);                 // Get All Users

// POST Routes
Route::post('/motoristas',[MotoristasController::class,'insertNewMotorista']);
Route::post('/clientes',[ClientesController::class,'insertNewCliente']);
Route::post('/camioes',[CamioesController::class,'insertNewCamiao']);
Route::post('/viagens',[ViagensController::class,'insertNewViagem']);
Route::post('/paragens',[ParagensController::class,'insertNewParagem']);
Route::post('/users',[UserController::class,'insertNewUser']);
Route::post('/login',[UserController::class,'login']);
Route::post('/logout',[UserController::class,'logout']);*/

// File upload routes
Route::post('temp_file', [TemporaryFileController::class,'saveTemporaryFile']);
