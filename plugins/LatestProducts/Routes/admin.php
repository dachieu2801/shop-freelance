<?php
/**
 * admin.php
 *

 * @created    2022-08-04 16:17:53
 * @modified   2022-08-04 16:17:53
 */

use Illuminate\Support\Facades\Route;
use Plugin\LatestProducts\Controllers\MenusController;

Route::get('/routes', [MenusController::class, 'getRoutes'])->name('routes');
