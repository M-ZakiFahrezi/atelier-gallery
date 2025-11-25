<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Import Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KolektorController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\KolektorEventController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('splash'))->name('splash');

Route::get('/home', [PublicController::class, 'home'])->name('home');
Route::get('/arts', [PublicController::class, 'arts'])->name('arts');
Route::get('/artists', [PublicController::class, 'artists'])->name('artists');
Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/leaderboard', [PublicController::class, 'leaderboard'])->name('leaderboard');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact/send', [PublicController::class, 'sendContact'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Login / Register
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Karya Seni
    Route::get('/karya', [AdminController::class, 'karyaIndex'])->name('karya.index');
    Route::get('/karya/create', [AdminController::class, 'karyaCreate'])->name('karya.create');
    Route::post('/karya/store', [AdminController::class, 'karyaStore'])->name('karya.store');
    Route::get('/karya/edit/{id}', [AdminController::class, 'karyaEdit'])->name('karya.edit');
    Route::put('/karya/update/{id}', [AdminController::class, 'karyaUpdate'])->name('karya.update');
    Route::delete('/karya/delete/{id}', [AdminController::class, 'karyaDestroy'])->name('karya.destroy');

    // Seniman
    Route::get('/seniman',       [AdminController::class, 'senimanIndex'])->name('seniman.index');
    Route::get('/seniman/create',[AdminController::class, 'senimanCreate'])->name('seniman.create');
    Route::post('/seniman/store',[AdminController::class, 'senimanStore'])->name('seniman.store');
    Route::get('/seniman/edit/{id}', [AdminController::class, 'senimanEdit'])->name('seniman.edit');
    Route::put('/seniman/update/{id}', [AdminController::class, 'senimanUpdate'])->name('seniman.update');
    Route::delete('/seniman/delete/{id}',[AdminController::class, 'senimanDestroy'])->name('seniman.destroy');

    // Galeri
    Route::get('/galeri', [AdminController::class, 'galeriIndex'])->name('galeri.index');
    Route::get('/galeri/create', [AdminController::class, 'galeriCreate'])->name('galeri.create');
    Route::post('/galeri/store', [AdminController::class, 'galeriStore'])->name('galeri.store');
    Route::get('/galeri/edit/{id}', [AdminController::class, 'galeriEdit'])->name('galeri.edit');
    Route::put('/galeri/update/{id}', [AdminController::class, 'galeriUpdate'])->name('galeri.update');
    Route::delete('/galeri/delete/{id}', [AdminController::class, 'galeriDestroy'])->name('galeri.destroy');

    // event
    Route::get('event', [EventController::class, 'index'])->name('event.index');
    Route::get('event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('event/store', [EventController::class, 'store'])->name('event.store');

    Route::get('event/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::post('event/{id}/update', [EventController::class, 'update'])->name('event.update');
    Route::delete('event/{id}', [EventController::class, 'destroy'])->name('event.destroy');
    
    Route::get('event/{id}/artworks', [EventController::class, 'artworks'])
        ->name('event.artworks');

    Route::post('event/{id}/artworks/add', [EventController::class, 'addArtwork'])
        ->name('event.artworks.add');

    Route::delete('event/artworks/{id}', [EventController::class, 'removeArtwork'])
        ->name('event.artworks.remove');
    

    // Orders Management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}/detail', [OrderController::class, 'detail'])->name('orders.detail');
    Route::post('orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');



    

    // Konfirmasi Transaksi
    Route::post('/transaksi/{id_transaksi}/confirm', 
        [TransactionController::class, 'adminConfirm']
    )->name('transaksi.confirm');
});

/*
|--------------------------------------------------------------------------
| Admin Profil (static view)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/profil', fn() => view('admin.profil'))->name('admin.profil');
    Route::get('/profil/edit', fn() => view('admin.editProfil'))->name('admin.editProfil');

    Route::post('/profil/update', function (\Illuminate\Http\Request $request) {
        $admin = session('admin');
        $admin->nama = $request->nama;
        $admin->username = $request->username;
        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        session(['admin' => $admin]);
        return redirect()->route('admin.profil')->with('success', 'Profil berhasil diperbarui.');
    })->name('admin.updateProfil');
});


/*
|--------------------------------------------------------------------------
| Dashboard Kolektor
|--------------------------------------------------------------------------
*/
Route::prefix('kolektor')->name('kolektor.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH Middleware Manual
    |--------------------------------------------------------------------------
    */
    $auth = function () {
        if (!session()->has('kolektor')) {
            return redirect()->route('login')
                ->withErrors(['loginError' => 'Silakan login terlebih dahulu.']);
        }
        return session('kolektor');
    };

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [KolektorController::class, 'dashboard'])
    ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | STATIC PAGES
    |--------------------------------------------------------------------------
    */
    Route::get('/home', fn() => view('kolektor.home', ['kolektor' => $auth()]))->name('home');
    Route::get('/arts', [KolektorController::class, 'arts'])->name('arts');
    Route::get('/artists', [KolektorController::class, 'artists'])->name('artists');
    Route::get('/gallery', [KolektorController::class, 'gallery'])->name('gallery');
    Route::get('/leaderboard', [KolektorController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/contact', fn() => view('kolektor.contact', ['kolektor' => $auth()]))->name('contact');

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profil', [KolektorController::class, 'profil'])->name('profil');
    Route::get('/profil/edit', fn() => view('kolektor.edit-profil', ['kolektor' => $auth()]))->name('profil.edit');
    Route::post('/profil/update', [AuthController::class, 'updateProfile'])->name('profil.update');

    /*
    |--------------------------------------------------------------------------
    | KOLEKTOR EVENT & PAMERAN
    |--------------------------------------------------------------------------
    */
    Route::get('/event', [KolektorEventController::class, 'index'])->name('event');
    Route::get('/event/{id}', [KolektorEventController::class, 'show'])->name('event.show');
    Route::get('/event/{id}/karya', [KolektorEventController::class, 'artworks'])->name('event.artworks');
    Route::post('/event/purchase', [KolektorEventController::class, 'purchase'])->name('event.purchase');

    Route::post('/favorit/{id_karya}', [KolektorController::class, 'toggleFavorit'])
    ->name('favorit.toggle');



//---------------------------------------------------------------------------------------
    Route::get('/my-gallery', [KolektorController::class, 'myGallery'])
        ->name('myGallery');
    Route::get('/favorites', [KolektorController::class, 'favorites'])
        ->name('favorites');
    
    // Order Tracking list page
    Route::get('/order-tracking', [KolektorController::class, 'orderTracking'])
        ->name('orderTracking');

    // Order Tracking detail (used by AJAX to populate modal)
    Route::get('/order-tracking/{id}', [KolektorController::class, 'orderTrackingDetail'])
        ->name('orderTracking.detail');



    // REMOVE FAVORITE
    Route::delete('/favorit/{id}', [KolektorController::class, 'removeFavorit'])
        ->name('favorit.remove');




//---------------------------------------------------------------------------------------

    /*
    |--------------------------------------------------------------------------
    | TRANSAKSI & CHECKOUT
    |--------------------------------------------------------------------------
    */
    Route::prefix('transaksi')->name('transaksi.')->group(function () {

        // Daftar transaksi kolektor
        Route::get('/', [TransactionController::class, 'index'])
            ->name('index');   // → route('kolektor.transaksi.index')

        // Halaman checkout
        Route::get('/checkout/{id_karya}', 
            [TransactionController::class, 'checkout'])
            ->name('checkout');

        // Proses checkout → membuat transaksi pending
        Route::post('/checkout/process', 
            [TransactionController::class, 'processCheckout'])
            ->name('checkout.process');

        // Detail transaksi
        Route::get('/detail/{id_transaksi}', 
            [TransactionController::class, 'show'])
            ->name('detail');

        // Halaman QRIS
        Route::get('/qris/{id_transaksi}', 
            [TransactionController::class, 'qris'])
            ->name('qris.show');

        // Form upload bukti
        Route::get('/qris/konfirmasi/{id_transaksi}', 
            [TransactionController::class, 'formKonfirmasiQris'])
            ->name('qris.form');

        // Submit bukti pembayaran
        Route::post('/qris/konfirmasi/{id_transaksi}', 
            [TransactionController::class, 'konfirmasiQris'])
            ->name('qris.konfirmasi');

        // Halaman sukses
        Route::get('/sukses/{id}', 
            [TransactionController::class, 'sukses'])
            ->name('sukses');

        // DIRECT API MIDTRANS (QR DINAMIS)
        Route::post('/payment/create', 
            [PaymentController::class, 'createPayment'])
            ->name('payment.create');
        
        // ➤ BATALKAN TRANSAKSI (POST)
        Route::post('/{id}/batalkan', 
            [TransactionController::class, 'batalkan']
        )->name('batalkan');

        // Detail transaksi (satu transaksi + riwayat kolektor)
        Route::get('/{id}/detail', [TransactionController::class, 'show'])
            ->name('detail');

        // Invoice view
        Route::get('/{id}/invoice', [TransactionController::class, 'invoice'])
            ->name('invoice');

        // Invoice PDF download
        Route::get('/{id}/invoice/pdf', [TransactionController::class, 'invoicePdf'])
            ->name('invoice.pdf');

        // (Opsional) refresh QR route if you use it
        Route::post('/{id}/refresh', [TransactionController::class, 'refreshQr'])
            ->name('refresh');

    });

});

// CALLBACK (HARUS DI LUAR)
Route::post('/payment/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback');

Route::get('/api/transaksi/status/{id}', [PaymentController::class, 'checkStatus'])
    ->name('transaksi.status');

