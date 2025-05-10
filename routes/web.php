<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('components.superduper.pages.home');
})->name('home');

Route::get('/blog', function () {
    return view('components.superduper.pages.coming-soon', ['page_type' => 'blog']);
})->name('blog.index');

Route::get('/blog/{blogId}', function ($blogId) {
    return view('components.superduper.pages.coming-soon', ['page_type' => 'blog_post', 'id' => $blogId]);
})->name('blog.show');

Route::get('/contact-us', function () {
    return view('components.superduper.pages.coming-soon', ['page_type' => 'contact']);
})->name('contact-us');

Route::get('/privacy-notice', function () {
    return view('components.superduper.pages.coming-soon', ['page_type' => 'privacy']);
})->name('privacy-notice');

// A dedicated route for the generic coming soon page
Route::get('/coming-soon', function () {
    return view('components.superduper.pages.coming-soon', ['page_type' => 'generic']);
})->name('coming-soon');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])
    ->name('contact.submit');

// TODO: Create actual blog preview component
Route::post('/blog-preview', function() {
    // Implementation pending
})->name('blog.preview');
