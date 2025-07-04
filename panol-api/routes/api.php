<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RepuestoController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\PuestoController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\MarcaController;
use App\Http\Controllers\Api\StockRepuestoController;
use App\Http\Controllers\Api\ComprobanteIngresoController;
use App\Http\Controllers\Api\TipoComprobanteController;
use App\Http\Controllers\Api\TurnoPanolController;
use App\Http\Controllers\Api\CocheController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TransferenciaRepuestoController;
use App\Http\Controllers\Api\InformeController;

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('repuestos', RepuestoController::class);

// Pon esto ANTES del apiResource:
Route::get('repuestos/resumen-stock', [StockRepuestoController::class, 'resumenStock']);

// Luego el resource:
Route::apiResource('repuestos', App\Http\Controllers\Api\RepuestoController::class);

// RUTAS PARA EMPLEADOS
Route::apiResource('empleados', App\Http\Controllers\Api\EmpleadoController::class);
Route::apiResource('puestos', App\Http\Controllers\Api\PuestoController::class);
Route::apiResource('empresas', App\Http\Controllers\Api\EmpresaController::class);
Route::get('/empresas', [EmpresaController::class, 'index']);
Route::get('/puestos', [PuestoController::class, 'index']);
Route::patch('puestos/{id}/desactivar', [App\Http\Controllers\Api\PuestoController::class, 'desactivar']);
Route::patch('empleados/{id}/desactivar', [App\Http\Controllers\Api\EmpleadoController::class, 'desactivar']);
Route::patch('coches/{id}/desactivar', [App\Http\Controllers\Api\CocheController::class, 'desactivar']);
//--------------------------------------

///para login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->post('/update-avatar', [AuthController::class, 'updateAvatar']);
///////////////
Route::apiResource('empresas', App\Http\Controllers\Api\EmpresaController::class);
Route::patch('empresas/{id}/desactivar', [App\Http\Controllers\Api\EmpresaController::class, 'desactivar']);
Route::apiResource('proveedores', App\Http\Controllers\Api\ProveedorController::class);
Route::patch('proveedores/{id}/desactivar', [App\Http\Controllers\Api\ProveedorController::class, 'desactivar']);
Route::apiResource('marcas', MarcaController::class);
Route::patch('marcas/{id}/desactivar', [MarcaController::class, 'desactivar']);
Route::patch('repuestos/{id}/desactivar', [App\Http\Controllers\Api\RepuestoController::class, 'desactivar']);
Route::post('/stock-repuestos/ingreso', [StockRepuestoController::class, 'ingreso']);
Route::get('/stock-repuestos/empresa/{empresa}', [StockRepuestoController::class, 'stockPorEmpresa']);
Route::post('/stock-repuestos/actualizar', [StockRepuestoController::class, 'actualizarStock']);
Route::get('/tipo-comprobante', [TipoComprobanteController::class, 'index']);
Route::get('/turnos-panol', [TurnoPanolController::class, 'index']);
Route::get('/proveedores', [ProveedorController::class, 'index']);
Route::get('/empresas', [EmpresaController::class, 'index']);
Route::get('/repuestos', [RepuestoController::class, 'index']);

Route::post('comprobante-ingreso', [ComprobanteIngresoController::class, 'store']);
Route::get('comprobantes', [ComprobanteIngresoController::class, 'index']);
Route::get('/repuestos-stock', [StockRepuestoController::class, 'index']);


Route::get('repuestos/resumen-stock', [StockRepuestoController::class, 'resumenStock']);
Route::post('comprobantes/{id}/anular', [ComprobanteIngresoController::class, 'anular']);
Route::apiResource('coches', App\Http\Controllers\Api\CocheController::class);
Route::apiResource('marcas-coches', App\Http\Controllers\Api\MarcaCocheController::class);
Route::apiResource('modelos', App\Http\Controllers\Api\ModeloController::class);
Route::apiResource('carrocerias', App\Http\Controllers\Api\CarroceriaController::class);
Route::get('empresas/{empresa}/coches', [CocheController::class, 'porEmpresa']);
Route::apiResource('services', ServiceController::class);
Route::get('/stock-negativo', [App\Http\Controllers\Api\StockRepuestoController::class, 'stockNegativo']);
Route::post('/transferencia-repuestos', [App\Http\Controllers\Api\TransferenciaRepuestoController::class, 'store']);
Route::get('/stock-negativo-todas', [TransferenciaRepuestoController::class, 'index']);
Route::get('coches/empresa/{empresaId}', [\App\Http\Controllers\Api\CocheController::class, 'porEmpresa']);
Route::post('services/{id}/cambiar-estado', [\App\Http\Controllers\Api\ServiceController::class, 'cambiarEstado']);
Route::post('services/{id}/actualizar-repuestos', [\App\Http\Controllers\Api\ServiceController::class, 'actualizarRepuestos']);
Route::get('services/kpis', [App\Http\Controllers\Api\ServiceController::class, 'kpis']);
Route::get('informes/stock-repuestos', [InformeController::class, 'stockRepuestos']);
Route::get('informes/servicios-realizados', [InformeController::class, 'serviciosRealizados']);



Route::get('tipos-servicio', function() {
    return response()->json([
        'data' => [
            ['id' => 1, 'descripcion' => 'Reparación'],
            ['id' => 2, 'descripcion' => 'Servicio'],
        ]
    ]);
});

Route::get('estados-servicio', function() {
    return response()->json([
        'data' => [
            ['id' => 1, 'descripcion' => 'Pendiente'],
            ['id' => 2, 'descripcion' => 'En Proceso'],
            ['id' => 3, 'descripcion' => 'Finalizado'],
            ['id' => 4, 'descripcion' => 'Cancelado'],
        ]
    ]);
});
Route::get('servicios/{id}/historial', [App\Http\Controllers\Api\ServiceController::class, 'historial']);


