<?php

//Route::get('articles/{id}', [
//    'as' => 'home',
//    'uses' => 'StoreController@index'
//]);
//Route::get('articles/{id}', function($id){
//    //return App\Product::where('id', $slug)->first();
//    return App\Product::find($id);
//});

//Route::bind('product', function ($value) {
//    return App\Product::find($value);
//});




//--se utilizara para el route cart/add/{product}
Route::bind('product', function($slug){
    return App\Product::where('slug', $slug)->first();
});

// Category dependency injection
Route::bind('category', function($category){
    return App\Category::find($category);
});

// User dependency injection
Route::bind('user', function($user){
    return App\User::find($user);
});



//// Category dependency injection
//Route::bind('category', function($category){
//    return App\Category::find($category);
//});
//// User dependency injection
//Route::bind('user', function($user){
//    return App\User::find($user);
//});
Route::get('/', [
    'as' => 'home',
    'uses' => 'StoreController@index'
]);


Route::get('product/{slug}', [
    'as' => 'product-detail',
    'uses' => 'StoreController@show'
]);

// Carrito -------------
Route::get('cart/show', [
    'as' => 'cart-show',
    'uses' => 'CartController@show'
]);
Route::get('cart/add/{product}', [
    'as' => 'cart-add',
    'uses' => 'CartController@add'
]);
Route::get('cart/delete/{product}',[
    'as' => 'cart-delete',
    'uses' => 'CartController@delete'
]);
Route::get('cart/trash', [
    'as' => 'cart-trash',
    'uses' => 'CartController@trash'
]);

//Route::get('cart/update/{product}/{quantity}', [
//    'as' => 'cart-update',
//    'uses' => 'CartController@update'
//]);

Route::get('cart/update/{product_slug}/{quantity}', [
    'as' => 'cart-update',
    'uses' => 'CartController@update'
]);





Route::group(["middleware" => ['auth']], function () {

    Route::group([ 'middleware' => ['is_user']], function () {

        Route::get('order-detail', [
            // 'middleware' => 'auth',
            //'middleware' => 'is_user',
            'as' => 'order-detail',
            'uses' => 'CartController@orderDetail'
        ]);

    });
});




//------------------------------------------------------------- Paypal
// Enviamos nuestro pedido a PayPal
Route::get('payment', array(
    'as' => 'payment',
    'uses' => 'PaypalController@postPayment',
));
// Después de realizar el pago Paypal redirecciona a esta ruta
Route::get('payment/status', array(
    'as' => 'payment.status',
    'uses' => 'PaypalController@getPaymentStatus',
));

//----------------------------------------------------------- ADMIN
//---------------------------1.que  proviene de la  carpeta Admin que esta  en controller
//---------------------------2. que  use  el middleware auth
//--
//Route::group(['namespace' => 'Admin', 'middleware' => ['auth'], 'prefix' => 'admin'], function()
//{
//
//
//
//});



Route::group(['prefix' => 'admin', 'middleware' => ['is_admin'], 'namespace' => 'Admin'], function () {

    Route::get('home', function(){
        return view('admin.home');
    });

    Route::resource('category', 'CategoryController', [
        'as' => 'admin' //router= admin.category.index
    ]);

    Route::resource('product', 'ProductController', [
        'as' => 'admin' //router= admin.category.index
    ]);

    Route::resource('user', 'UserController', [
        'as' => 'admin' //router= admin.category.index
    ]);



    Route::get('orders', [
        'as' => 'admin.order.index',
        'uses' => 'OrderController@index'
    ]);

    Route::post('order/get-items', [
        'as' => 'admin.order.getItems',
        'uses' => 'OrderController@getItems'
    ]);

    Route::get('order/{id}', [
        'as' => 'admin.order.destroy',
        'uses' => 'OrderController@destroy'
    ]);
});
/*



Route::get('admin/home', ['as' => 'admin.home', function () {
    //return "dd";
    return view('admin.home');
}]);


//Route::resource('admin/category', 'Admin\CategoryController');

Route::resource('admin/category', 'Admin\CategoryController', [
    'as' => 'admin' //router= admin.category.index
]);
Route::resource('admin/product', 'Admin\ProductController', [
    'as' => 'admin' //router= admin.category.index
]);

Route::resource('admin/user', 'Admin\UserController', [
    'as' => 'admin' //router= admin.category.index
]);


Route::get('admin/orders', [
    'as' => 'admin.order.index',
    'uses' => 'Admin\OrderController@index'
]);

Route::post('admin/order/get-items', [
    'as' => 'admin.order.getItems',
    'uses' => 'Admin\OrderController@getItems'
]);

Route::get('admin/order/{id}', [
    'as' => 'admin.order.destroy',
    'uses' => 'Admin\OrderController@destroy'
]);


*/


Auth::routes();

