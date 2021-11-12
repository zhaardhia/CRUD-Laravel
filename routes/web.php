<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MahasiswaController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/',[MahasiswaController::class, 'index']);
Route::post('/add-student',[MahasiswaController::class, 'addStudent'])->name('add.student');
Route::get('/student/list',[MahasiswaController::class, 'studentsList'])->name('get.students.list');
Route::post('/student/details',[MahasiswaController::class, 'studentsDetails'])->name('get.students.details');
Route::post('/update/studentDetails',[MahasiswaController::class, 'updateStudentsDetails'])->name('update.student.details');
Route::post('delete/student',[MahasiswaController::class, 'deleteStudent'])->name('delete.student');

Route::get('/downloadPdf',[MahasiswaController::class, 'downloadPdf'])->name('download.pdf');

// Route::get('/downloadPdf',[MahasiswaController::class,'downloadPdf'] function () {
//     // return view('letter',[
        
//     // ])
//     return view('letter');
// });