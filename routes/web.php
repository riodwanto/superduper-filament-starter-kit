<?php

use App\Livewire\SuperDuper\BlogList;
use App\Livewire\SuperDuper\BlogDetails;
use App\Livewire\SuperDuper\Pages\ContactUs;
use Illuminate\Support\Facades\Route;
use Lab404\Impersonate\Services\ImpersonateManager;

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

Route::get('/about-us', function () {
    return view('components.superduper.pages.about');
})->name('about-us');


Route::get('/blog', BlogList::class)->name('blog');

Route::get('/blog/{slug}', BlogDetails::class)->name('blog.show');

Route::get('/contact-us', ContactUs::class)->name('contact-us');

Route::get('/privacy-policy', function () {
    return view('components.superduper.pages.coming-soon');
})->name('privacy-policy');

Route::get('/terms-conditions', function () {
    return view('components.superduper.pages.coming-soon');
})->name('terms-conditions');

Route::get('/coming-soon', function () {
    return view('components.superduper.pages.coming-soon');
})->name('coming-soon');

Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])
    ->name('contact.submit');

// TODO: Create actual blog preview component
Route::post('/blog-preview', function() {
    // Implementation pending
})->name('blog.preview');

Route::get('impersonate/leave', function() {
    if(!app(ImpersonateManager::class)->isImpersonating()) {
        return redirect('/');
    }

    app(ImpersonateManager::class)->leave();

    return redirect(
        session()->pull('impersonate.back_to')
    );
})->name('impersonate.leave')->middleware('web');

