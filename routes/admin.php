<?php

use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminLoaderController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminIgnoreController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminWhitelistController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminModController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminUIController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminServerController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminRpcController;
use Azuriom\Plugin\Centralcorp\Controllers\Admin\AdminRoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::get('/', function () {
    return view('centralcorp::admin.index');
})->name('index');

Route::get('/server', [AdminServerController::class, 'show'])->name('server');
Route::post('/server/update', [AdminServerController::class, 'update'])->name('server.update');

Route::get('/ui', [AdminUIController::class, 'show'])->name('ui');
Route::post('/ui/update', [AdminUIController::class, 'update'])->name('ui.update');

Route::get('/whitelist', [AdminWhitelistController::class, 'index'])->name('whitelist');
Route::post('/whitelist', [AdminWhitelistController::class, 'store'])->name('whitelist.store');
Route::delete('/whitelist/user/{id}', [AdminWhitelistController::class, 'destroyUser'])->name('whitelist.destroyUser');
Route::delete('/whitelist/role/{id}', [AdminWhitelistController::class, 'destroyRole'])->name('whitelist.destroyRole');

Route::get('/mods', [AdminModController::class, 'index'])->name('mods');
Route::post('/mods/add', [AdminModController::class, 'addOptionalMod'])->name('mods.addOptional');
Route::post('/mods/update', [AdminModController::class, 'updateOptionalMod'])->name('mods.updateOptional');
Route::post('/mods/delete/{id}', [AdminModController::class, 'deleteOptionalMod'])->name('mods.delete');
Route::get('/mods/{id}', [AdminModController::class, 'getOptionalModDetails'])->name('mods.getOptionalModDetails');

Route::get('/loader', [AdminLoaderController::class, 'index'])->name('loader');
Route::post('/loader/update', [AdminLoaderController::class, 'update'])->name('loader.update');
Route::get('/loader/builds', [AdminLoaderController::class, 'getForgeBuilds'])->name('loader.builds');
Route::get('/loader/fabric-versions', [AdminLoaderController::class, 'getFabricVersions'])->name('loader.fabric-versions');


Route::get('/rpc', [AdminRpcController::class, 'show'])->name('rpc');
Route::post('/rpc/update', [AdminRpcController::class, 'update'])->name('rpc.update');

Route::get('/general', [AdminController::class, 'general'])->name('general');
Route::post('/general', [AdminController::class, 'updateGeneral'])->name('general.update');

Route::get('/ignore', [AdminIgnoreController::class, 'index'])->name('ignore');
Route::post('/ignore', [AdminIgnoreController::class, 'store'])->name('ignore.store');
Route::delete('/ignore/folder/{id}', [AdminIgnoreController::class, 'destroyFolder'])->name('ignore.destroyFolder');

Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
Route::post('/roles/update', [AdminRoleController::class, 'update'])->name('roles.update');


