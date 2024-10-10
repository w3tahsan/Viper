<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PassResetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

//Frontend
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/author/signin', [FrontendController::class, 'author_signin'])->name('author.signin');
Route::get('/author/signup', [FrontendController::class, 'author_signup'])->name('author.signup');
Route::get('/author/list', [FrontendController::class, 'author_list'])->name('author.list');

require __DIR__ . '/auth.php';

//backend
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Profile
Route::post('/add/user', [UserController::class, 'add_user'])->name('add.user');
Route::get('/users', [UserController::class, 'users'])->name('users');
Route::get('/edit/profile', [UserController::class, 'edit_profile'])->name('edit.profile');
Route::post('/update/profile', [UserController::class, 'update_profile'])->name('update.profile');
Route::post('/update/password', [UserController::class, 'update_password'])->name('update.password');
Route::post('/update/photo', [UserController::class, 'update_photo'])->name('update.photo');
Route::get('/user/delete/{user_id}', [UserController::class, 'user_delete'])->name('user.delete');


//Category
Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
Route::post('/category/store', [CategoryController::class, 'category_store'])->name('category.store');
Route::get('/category/edit/{category_id}', [CategoryController::class, 'category_edit'])->name('category.edit');
Route::post('/category/update/{category_id}', [CategoryController::class, 'category_update'])->name('category.update');
Route::get('/category/delete/{category_id}', [CategoryController::class, 'category_delete'])->name('category.delete');
Route::get('/category/restore/{category_id}', [CategoryController::class, 'category_restore'])->name('category.restore');
Route::get('/category/hard/delete/{category_id}', [CategoryController::class, 'category_hard_delete'])->name('category.hard.delete');
Route::post('/category/check_delete', [CategoryController::class, 'category_check_delete'])->name('category.check.delete');
Route::post('/category/check/restore', [CategoryController::class, 'category_check_restore'])->name('category.check.restore');

//Tags
Route::get('/tags', [TagController::class, 'tags'])->name('tags');
Route::post('/tag/store', [TagController::class, 'tag_store'])->name('tag.store');
Route::get('/tag/delete/{tag_id}', [TagController::class, 'tag_delete'])->name('tag.delete');

//Authors
Route::post('/author/register', [AuthorController::class, 'author_register'])->name('author.register');
Route::post('/author/login', [AuthorController::class, 'author_login'])->name('author.login');
Route::get('/author/logout', [AuthorController::class, 'author_logout'])->name('author.logout');
Route::get('/authors', [UserController::class, 'authors'])->name('authors');
Route::get('/author/delete/{author_id}', [UserController::class, 'author_delete'])->name('author.delete');
Route::get('/authors/status/{author_id}', [UserController::class, 'author_status'])->name('author.status');
Route::get('/authors/dashboard', [AuthorController::class, 'author_dashboard'])->name('author.dashboard');
Route::get('/authors/edit', [AuthorController::class, 'author_edit'])->name('author.edit');
Route::post('/authors/profile/update', [AuthorController::class, 'author_profile_update'])->name('author.profile.update');
Route::post('/authors/pass/update', [AuthorController::class, 'author_pass_update'])->name('author.pass.update');
Route::get('/authors/verify/{token}', [AuthorController::class, 'author_verify'])->name('author.verify');
Route::get('/request/verify', [AuthorController::class, 'request_verify'])->name('request.verify');
Route::post('/request/verify/send', [AuthorController::class, 'request_verify_send'])->name('request.verify.send');


//post
Route::get('/add/post', [PostController::class, 'add_post'])->name('add.post');
Route::post('/post/store', [PostController::class, 'post_store'])->name('post.store');
Route::get('/my/post', [PostController::class, 'my_post'])->name('my.post');
Route::get('/my/post/status/{post_id}', [PostController::class, 'my_post_status'])->name('my.post.status');
Route::get('/my/post/delete/{post_id}', [PostController::class, 'my_post_delete'])->name('my.post.delete');
Route::get('/post/details/{slug}', [FrontendController::class, 'post_details'])->name('post.details');
Route::get('/author/post/{author_id}', [FrontendController::class, 'author_post'])->name('author.post');
Route::get('/category/post/{category_id}', [FrontendController::class, 'category_post'])->name('category.post');


//search
Route::get('/search', [FrontendController::class, 'search'])->name('search');
Route::get('/tag/post/{tag_id}', [FrontendController::class, 'tag_post'])->name('tag.post');

//comments
Route::post('/comment/store', [FrontendController::class, 'comment_store'])->name('comment.store');

// Role
Route::get('/role', [RoleController::class, 'role'])->name('role');
Route::post('/permission/store', [RoleController::class, 'permission_store'])->name('permission.store');
Route::post('/role/store', [RoleController::class, 'role_store'])->name('role.store');
Route::post('/role/assign', [RoleController::class, 'role_assign'])->name('role.assign');
Route::get('/role/delete/{role_id}', [RoleController::class, 'role_delete'])->name('role.delete');
Route::get('/role/remove/{user_id}', [RoleController::class, 'role_remove'])->name('role.remove');

//Password Reset
Route::get('pass/reset/req', [PassResetController::class, 'pass_reset_req'])->name('pass.reset.req');
Route::post('pass/reset/req/send', [PassResetController::class, 'pass_reset_req_post'])->name('pass.reset.req.post');
Route::get('pass/reset/form/{token}', [PassResetController::class, 'pass_reset_form'])->name('pass.reset.form');
Route::post('pass/reset/update/{token}', [PassResetController::class, 'pass_reset_update'])->name('pass.reset.update');

//FAQ
Route::resource('faq', FaqController::class);
