<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2020/1/19
 * Time: 19:21
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\View;
use App\Http\Models\File;

class FileController extends CommonController
{

    protected function _delete($id) {
        $file = new File();
        return $file->deleteFile($id);
    }

    public function delete() {
        $file = File::find (request('id'));
        if ($this->_delete (request('id'))) {
            return $this->success ( $file ['name'] . '，素材文件删除成功', route('admin.file.index'),'','',false);
        }else {
            return $this->error('删除失败', route('admin.file.index'),'','',false);
        }
    }

    public function index() {
        $map = [];
        $map2 = [];
        if (request('search', '') == 'go') {
            $keywords = request('keywords', '');
            $map[] = ['name','like','%' . $keywords . '%'];
            $map2[] = ['savename','like','%' . $keywords . '%'];
        }

        if (!empty(request('album_name', ''))) {
            $map['config'] = request('album_name');
        }

        $file = new File();
        $list = $file
            ->where ( $map )
            ->whereOr ( $map2 )
            ->orderBy ('id','desc')
            ->paginate( $this->listrows())->appends($_GET);
        View::share('list', $list );
        View::share('albums', albumName(null, true));

        return view('file.index');
    }

    // 文件下载
    public function download() {
        $file =  File::find(request('id'));
        $file_path = $file->getRealPath(request('id'));
        return  response()->download($file_path,basename($file_path),$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
    }
}