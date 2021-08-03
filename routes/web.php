<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CalenderController;

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
Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
//Public routes
//Route::get('/', 'GeneralController@index')->name('public.index');

Route::redirect('/', '/dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', 'GeneralController@dashboard')->name('dashboard');

Route::group(['middleware' => 'auth'], function () {
    Route::get('tasks/all', 'TaskController@alltasks')->name('tasks.alltasks');
    Route::put('tasks/{id}', 'TaskController@complete')->name('tasks.complete');
    Route::get('tasks/pending', 'TaskController@pending')->name('tasks.pending');
    Route::patch('tasks/accept/{id}', 'TaskController@accept')->name('tasks.accept');
    Route::post('tasks/requestchange/{id}', 'TaskController@requestchange')->name('tasks.requestchange');
    Route::put('tasks/update/{id}', 'TaskController@update')->name('tasks.updatetask');
    Route::get('tasks/teamtasks', 'TaskController@teamoverview')->name('tasks.teamoverview');
    Route::delete('tasks/delete/{task}', 'TaskController@destroy')->name('tasks.delete');
    Route::resource('tasks', TaskController::class);

    Route::resource('users', UserController::class);

    Route::get('tremenheere/pos/payment', '\OrderController@reviewOrder')->name('tremenheere.pos.topayment');
    Route::post('tremenheere/pos/payment', 'OrderController@placeOrder')->name('tremenheere.pos.placeorder');
    Route::get('tremenheere/pos/category', 'TremenheereController@viewcategory')->name('tremenheere.pos.viewcategory');
    Route::get('tremenheere/pos/misccategory', 'TremenheereController@miscellaneous')->name('tremenheere.pos.getmiscellaneous');
    Route::get('tremenheere/pos/variant', 'TremenheereController@varianttocart')->name('tremenheere.pos.varianttocart');
    Route::get('tremenheere/pos/product', 'TremenheereController@addtocart')->name('tremenheere.pos.addtocart');
    Route::patch('tremenheere/pos', 'TremenheerePOSController@update')->name('tremenheere.pos.updatecart');
    Route::delete('tremenheere/pos', 'TremenheerePOSController@remove')->name('tremenheere.pos.removefromcart');
    Route::resource('tremenheere/pos', TremenheerePOSController::class, [
        'names' => [
            'index' => 'tremenheere.pos.index',

        ]
    ]);



    Route::get('tremenheere/stock/product/{id}', 'TremenheereStockController@showproduct')->name('tremenheere.stock.showproduct');
    Route::post('tremenheere/stock/store', 'TremenheereStockController@store')->name('tremenheere.stock.store');
    Route::post('tremenheere/category/store', 'TremenheereStockController@categorystore')->name('tremenheere.stock.categorystore');
    Route::get('tremenheere/stock/category/create', 'TremenheereStockController@createcategory')->name('tremenheere.stock.createcategory');
    Route::get('tremenheere/stock/category/edit/{id}', 'TremenheereStockController@editcategory')->name('tremenheere.stock.editcategory');
    Route::patch('tremenheere/stock/category/update/{id}', 'TremenheereStockController@updatecategory')->name('tremenheere.stock.updatecategory');
    Route::delete('tremenheere/stock/product/delete/{id}', 'TremenheereStockController@deleteproduct')->name('tremenheere.stock.deleteproduct');
    Route::delete('tremenheere/stock/category/delete/{id}', 'TremenheereStockController@deletecategory')->name('tremenheere.stock.deletecategory');
    Route::resource('tremenheere/stock', TremenheereStockController::class, [
        'names' => [
            'index' => 'tremenheere.stock.index',
            'show' => 'tremenheere.stock.show',
            'update' => 'tremenheere.stock.update',
            'create' => 'tremenheere.stock.create',
        ]
    ]);

    Route::get('tremenheere/labels', 'LabelController@index')->name('tremenheere.labels.index');
    Route::get('tremenheere/labels/requests', 'LabelController@requests')->name('tremenheere.labels.requests');
    Route::post('tremenheere/labels/order/update', 'LabelController@update')->name('tremenheere.labels.update');
    Route::delete('tremenheere/labels/order/delete', 'LabelController@delete')->name('tremenheere.labels.delete');
    Route::patch('tremenheere/labels/order/updateorder', 'LabelController@orderupdate')->name('tremenheere.labels.orderupdate');
    Route::post('tremenheere/labels/order/completeorder', 'LabelController@completeorder')->name('tremenheere.labels.completeorder');
    Route::get('tremenheere/labels/request/{id}', 'LabelController@requestshow')->name('tremenheere.labels.requestshow');
    Route::get('tremenheere/labels/request/print/{id}', 'LabelController@pdf')->name('tremenheere.labels.pdf');
    Route::put('tremenheere/labels/request/complete/{id}', 'LabelController@completerequest')->name('tremenheere.labels.completerequest');

    Route::get('alerts/{id}', 'AlertController@edit')->name('alerts.edit');
    Route::delete('alerts/delete/{alert}', 'AlertController@destroy')->name('alerts.destroy');
    
    Route::patch('/posts/like/{id}', 'LikeController@postlike')->name('post.like');
    Route::patch('/posts/unlike/{id}', 'LikeController@postunlike')->name('post.unlike');
    Route::patch('/posts/dislike/{id}', 'LikeController@postdislike')->name('post.dislike');
    Route::patch('/posts/undislike/{id}', 'LikeController@postundislike')->name('post.undislike');
    
    Route::get('/training', 'TrainingController@index')->name('training.index');
    Route::get('/training/create', 'TrainingController@create')->name('training.create');
    Route::post('/training/store', 'TrainingController@store')->name('training.store');
    Route::get('/training/{id}', 'TrainingController@show')->name('training.show');
    Route::get('/training/edit/{id}', 'TrainingController@edit')->name('training.edit');
    Route::put('/training/update/{id}', 'TrainingController@update')->name('training.update');
    Route::delete('/training/delete/{id}', 'TrainingController@delete')->name('training.delete');

    Route::get('posts/create', 'PostController@create')->name('posts.create');
    Route::post('posts', 'PostController@store')->name('posts.store');
    Route::get('/posts', 'PostController@index')->name('posts.index');
    Route::get('/posts/view/{post:slug}', 'PostController@show')->name('posts.show');
    Route::post('/comment/store', 'CommentController@store')->name('comment.add');
    Route::post('/posts/reply/store', 'CommentController@replyStore')->name('reply.add');
    Route::post('/posts/delete/{id}', 'PostController@destroy')->name('posts.delete');
    Route::post('/posts/vote/{id}', 'PostController@update')->name('posts.vote');

    Route::get('/calendar', 'CalendarController@index')->name('calendar.index');
    Route::get('/calendar/json', 'CalendarController@json')->name('calendar.getjson');

    Route::post('/calendar/json', 'CalendarController@json')->name('calendar.postjson');
    Route::post('/calendar/action', 'CalendarController@action')->name('calendar.action');
    Route::patch('/calendar/edit', 'CalendarController@editevent')->name('calendar.editevent');
    Route::patch('/calendar/delete', 'CalendarController@deleteevent')->name('calendar.delete');
    
    Route::get('/propagation/trereife', 'PropagationController@trereife')->name('propagation.trereife');
    Route::get('/propagation/trewidden', 'PropagationController@trewidden')->name('propagation.trewidden');
    Route::put('/propagation/update', 'PropagationController@update')->name('propagation.update');
    Route::get('/propagation/create/{source}', 'PropagationController@create')->name('propagation.create');
    Route::post('/propagation/store', 'PropagationController@store')->name('propagation.store');
    Route::delete('/propagation/delete', 'PropagationController@delete')->name('propagation.delete');


    Route::get('/images/tasks/{file}', [ function ($file) {

        $path = storage_path('app/images/tasks/'.$file);
        
        if (file_exists($path)) {
    
            return response()->file($path, array('Content-Type' =>'image/jpeg'));
    
        }
    
        abort(404);
    
    }]);
    Route::get('/images/products/{file}', [ function ($file) {

        $path = storage_path('app/images/products/'.$file);
        
        if (file_exists($path)) {
    
            return response()->file($path, array('Content-Type' =>'image/jpeg'));
    
        }
    
        abort(404);
    
    }]);

    Route::get('/images/categories/{file}', [ function ($file) {

        $path = storage_path('app/images/categories/'.$file);
        
        if (file_exists($path)) {
    
            return response()->file($path, array('Content-Type' =>'image/jpeg'));
    
        }
    
        abort(404);
    
    }]);

});

