<?php

use Illuminate\Support\Facades\Route;
use App\InterfaceAdapters\Controllers\LoginController;
use App\InterfaceAdapters\Controllers\MediaController;
use App\InterfaceAdapters\Controllers\DocumentController;
use App\InterfaceAdapters\Controllers\ZenodoAPIController;
use App\InterfaceAdapters\Controllers\DocumentTypeController;
use App\InterfaceAdapters\Controllers\DocumentMetadataController;

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

Route::post('/login', [LoginController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/document_meta_data/{id}', [DocumentMetadataController::class,'getDocumentMetadataById']);
    Route::get('/document_types', [DocumentTypeController::class,'getAllDocumentTypes']);
    Route::get('/document/{id}', [DocumentController::class,'getDocumentById']);
    Route::get('/document-list', [DocumentController::class,'getDocumentsByFilter']);
    Route::get('/document-list/{userId}', [DocumentController::class,'getDocumentsByUserId']);
    Route::get('/zenodo-document/{id}', [ZenodoAPIController::class,'getDocumentById']);
    Route::get('/zenodo-document', [ZenodoAPIController::class,'getDocumentList']);

    // POST Routes
    Route::post('/document', [DocumentController::class,'submitNewDocument']);

    Route::post('/logout', [LoginController::class,'logout']);

    // PUT Routes
    Route::put('/document/{id}', [DocumentController::class,'editDocument']);

    // DELETE Routes
    Route::delete('/document/{id}', [DocumentController::class,'deleteDocument']);

    // File upload routes
    Route::post('temp_file', [MediaController::class,'saveTemporaryFile']);
});
