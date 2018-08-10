<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UploadsManager;
use Illuminate\Http\Request;
use App\Http\Requests\File\UploadFileRequest;
// use App\Http\Requests\UploadNewFolderRequest;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    protected $manager;

    public function __construct(UploadsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Show page of files / subfolders
     */
    public function index(Request $request)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);

        return view('admin.upload.index', $data);
    }

    // 添加如下四个方法到UploadController控制器类
    /**
    * 创建新目录
    */
    public function createFolder(Request $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder').'/'.$new_folder;
    
        $result = $this->manager->createDirectory($folder);
        
        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("Folder '$new_folder' created.");
        }
    
        $error = $result ? : "An error occurred creating directory.";
        return redirect()
                ->back()
                ->withErrors([$error]);
    }
    
    /**
     * 删除文件
     */
    public function deleteFile(Request $request)
    {
        $del_file = $request->get('del_file');
        $path = $request->get('folder').'/'.$del_file;
    
        $result = $this->manager->deleteFile($path);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("File '$del_file' deleted.");
        }
    
        $error = $result ? : "An error occurred deleting file.";
        return redirect()
                ->back()
                ->withErrors([$error]);
    }
    
    /**
     * 删除目录
     */
    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder').'/'.$del_folder;
    
        $result = $this->manager->deleteDirectory($folder);
    
        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("Folder '$del_folder' deleted.");
        }
    
        $error = $result ? : "An error occurred deleting directory.";
        return redirect()
                ->back()
                ->withErrors([$error]);
    }
    
    /**
     * 上传文件
     */
    public function uploadFile(UploadFileRequest $request)
    {
        
        $file = $_FILES['file'];

        $fileName = $request->get('file_name');

        $fileName = $fileName ?: $file['name'];

        // dd($file);

        //文件类型约束
        $file_types=array('xls','xlsx','csv'); 

        $up_filename=$file['name'];
        $filename_arr=explode('.', $up_filename);
        $file_ext=array_pop($filename_arr);
        if(!in_array($file_ext,$file_types))
        {
            return redirect()
                ->back()
                ->withErrors('您上传的文件类型不对！目前只支持'. implode(',', $file_types));

            return false;
        }

        //文件大小约束
        if($file['size'] > 2*1024*1024){

            return redirect()
                ->back()
                ->withErrors('您上传的文件超过2MB');
        }

        $fileName = date('Y-m-d-h-i-s').'.'.$file_ext;
        $path = str_finish($request->get('folder'), '/') . iconv('UTF-8', 'GBK', $fileName);
        $content = File::get($file['tmp_name']);

        /*p(public_path('uploads/dianxinExcel'));
        p($path);
        dd($path.'/'.$fileName);*/

        $result = $this->manager->saveFile($path, $content);

        // dd($result);

        if ($result === true) {
            return redirect()
                    ->route('infoDianxin.importExcel')
                    ->withFileName($fileName);
        }
    
        $error = $result ? : "错误文件请重试";
        return redirect()
                ->back()
                ->withErrors([$error]);
    }
}