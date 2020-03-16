<?php

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

/**
 * prefix 组中所有路由的 URI 加上 admin 前缀
 * name 以 admin 为所有分组路由的名称加前缀
 * namespace 在 「App\Http\Controllers\Admin」 命名空间下的控制器
 */


    Route::namespace('Admin')->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    //必须登录
    Route::middleware(['LoginCheck'])->group(function(){
        Route::get('/index/index','IndexController@index')->name('index');
        Route::get('/login/out','LoginController@out')->name('login.out');//退出
        Route::any('/settings/form','SettingsController@form')->name('settings.form');//系统设置
        Route::get('/links/index','LinksController@index')->name('links.index');//友情链接
        Route::any('/links/form','LinksController@form')->name('links.form');//友情链接
        Route::post('/links/doAction','LinksController@doAction')->name('links.doAction');
        Route::any('/links/delete','LinksController@delete')->name('links.delete');
        Route::get('/file/index','FileController@index')->name('file.index');//友情链接
        Route::get('/file/download','FileController@download')->name('file.download');
        Route::get('/file/delete','FileController@delete')->name('file.delete');
        Route::get('/admin/index','AdminController@index')->name('admin.index');
        Route::any('/admin/form','AdminController@form')->name('admin.form');
        Route::get('/admin/delete','AdminController@delete')->name('admin.delete');
        Route::get('/adminRole/index','adminRoleController@index')->name('adminRole.index');
        Route::any('/adminRole/form','adminRoleController@form')->name('adminRole.form');
        Route::get('/adminRole/delete','adminRoleController@delete')->name('adminRole.delete');
        Route::get('/oplog/index','OplogController@index')->name('oplog.index');
        Route::get('/loginlog/index','LoginlogController@index')->name('loginlog.index');
        Route::get('/article/index','ArticleController@index')->name('article.index');
        Route::any('/article/form','ArticleController@form')->name('article.form');
        Route::get('/article/delete','ArticleController@delete')->name('article.delete');
        Route::get('/article/tags','ArticleController@tags')->name('article.tags');
        Route::post('/article/doAction','ArticleController@doAction')->name('article.doAction');

        Route::get('/articleCategory/index','ArticleCategoryController@index')->name('articleCategory.index');
        Route::any('/articleCategory/form','ArticleCategoryController@form')->name('articleCategory.form');
        Route::get('/articleCategory/delete','ArticleCategoryController@delete')->name('articleCategory.delete');
        Route::post('/articleCategory/doAction','ArticleCategoryController@doAction')->name('articleCategory.doAction');

        Route::get('/articleTags/index','ArticleTagsController@index')->name('articleTags.index');
        Route::get('/articleTags/delete','ArticleTagsController@delete')->name('articleTags.delete');

        Route::get('/articleComment/index','ArticleCommentController@index')->name('articleComment.index');
        Route::post('/articleComment/doAction','ArticleCommentController@doAction')->name('articleComment.doAction');
        Route::any('/articleComment/preview','ArticleCommentController@preview')->name('articleComment.preview');
        Route::get('/articleComment/delete','ArticleCommentController@delete')->name('articleComment.delete');


        Route::get('/member/index','MemberController@index')->name('member.index');
        Route::get('/member/delete','MemberController@delete')->name('member.delete');
        Route::any('/member/charge','MemberController@charge')->name('member.charge');
        Route::any('/member/preview','MemberController@preview')->name('member.preview');

        Route::get('/memberLoginlog/index','MemberLoginlogController@index')->name('memberLoginlog.index');

        Route::get('/memberFinance/index','MemberFinanceController@index')->name('memberFinance.index');


        Route::post('/upload/editorup','UploadController@editorup')->name('upload.editorup');
        Route::get('/upload/uploadify','UploadController@uploadify')->name('upload.uploadify');
        Route::post('/upload/up','UploadController@up')->name('upload.up');
    });


    Route::get('/login/index','LoginController@index')->name('login.index'); //登录页面
    Route::post('/login/check','LoginController@check')->name('login.check'); //登录请求




});


