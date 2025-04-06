<?php

use Illuminate\Support\Facades\Route;
//use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use App\Http\Controllers\JobController;
use Illuminate\Contracts\Queue\Job;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
//this is the cleanest way bec i am passing variables only using compact
Route::resource('jobs',JobController::class);
Route::get('/jobs/share', [JobController::class, 'share']);




/*
Route::get('/jobs4',function(){
    $title = "Available Jobs";
    return view('jobs.index')->with(
        'title',$title
    );
})->name('jobs');


Route::get('/jobs3',function(){
    return view('jobs.index')->with(
        'title','Available Jobs'
    );
})->name('jobs');
*/





/*
Route::get('/jobs',function(){
    return view('jobs.index',[
        'title'=> 'Available Jobs'
    ]);
})->name('jobs');
*/



/*
Route::match(['get','post'],'/submit',function(){
    return 'Submitted';
});//submit form
// if i tried to submit form to db from another ip address i will get error+
//i need to add csrf

Route::get('/test',function(){
    $url = route('jobs');
    return "<a href='$url'>click here</a>";
});

Route::get('/api/users',function(){
    return [
        'name'=> 'behery',
        'email'=> 'beherym884@gmail.com'
    ];
}); //return json
*/
/*
Route::get('/posts/{id}',function(string $id){
    return 'Post '.$id;
});//->where('id','[0-9]+');
//whereNmber || whereAlpha

Route::get('/posts/{id}/comments/{commentId}',function(string $id, string $commentId){
    return 'Post '.$id.' Comment '.$commentId ;
});
*/

/*
//it returns the request of the url used
Route::get('/test',function(Request $request){
    return [
        'method'=>$request->method(),
        'url'=>$request->url(),
        'path'=>$request->path(),
        'fullUrl'=>$request->fullUrl(),
        'ip'=>$request->ip(),
        'userAgent'=>$request->userAgent(),
        'header'=>$request->header(),
    ];
});

//functions on request object
Route::get('/users' , function(Request $request){
    return $request->only(['name','age']);
    //query , has , only , except , all,input
});
*/


/*RESPONSE OBJECT*/
/*

Route::get('/test' ,function(){
    return response('<h1>hello world</h1>', 200)->header('Content-Type','text/html');
}); //content type here means type of text on screen

Route::get('/bad-request' ,function(){
    return new Response('Bad Request', 400);
});

Route::get('/test2', function () {
    return response('Cookie has been set')->json(['name' => 'zizo'])->cookie('name', 'ismail', 60);
});
Route::get('/download' ,function(){
    return response()->download(public_path('favicon.ico'));
});

Route::get('/read-cookie',function(Request $request){
    $cookieValue = $request->cookie('name');
    return response()->json(['cookie'=>$cookieValue]);
});
*/
