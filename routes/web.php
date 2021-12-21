<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/get-courses', function () {
    return view('courses.index');
});

Route::get('/folders', function () {
    return view('folders.index');
});

Route::get('/get-courses/{id}', function ($id) {
    return view('courses.show')->with(['courseId' => $id]);
});

Route::get('/folders/{id}', function ($id) {
    return view('folders.show')->with(['folderId' => $id]);
});

Route::get('/course-folders/{id}', function ($id) {
    return view('folders.course-folders')->with(['courseId' => $id]);
});

Route::get('/get-students', function () {
    return view('students.index');
});
Route::get('/get-students/{id}', function ($id) {
    return view('students.show')->with(['studentId' => $id]);
});

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/students', 'StudentController')->middleware('auth');

Route::post('/courses/suscribe/{id}', 'CourseController@suscribe')
    ->name('courses.suscribe')
    ->middleware('auth');
Route::post('/courses/unsuscribe/{id}', 'CourseController@unsuscribe')
    ->name('courses.unsuscribe')
    ->middleware('auth');
Route::get('/courses/stats', 'CourseController@stats')->middleware('auth');
Route::resource('/courses', 'CourseController')->middleware('auth');
Route::resource('/folders', 'FolderController')->middleware('auth');
Route::get('/coursefolders/{id}', 'FolderController@courseFolders')
    ->name('coursefolders')
    ->middleware('auth');
