<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
// use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Package\PackageRepositoryContract;
//use App\Http\Requests\Package\UpdatePackageRequest;
//use App\Http\Requests\Package\StorePackageRequest;

class PackageController extends Controller
{
    protected $package;

    public function __construct(

        PackageRepositoryContract $package
    ) {
    
        $this->package = $package;

        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $packages = $this->package->getAllPackage();
        // dd(lastSql());
        // dd($packages);
        /*foreach ($category as $key => $value) {
           dd($value->belongsToShop);
        }*/
        return view('admin.package.index', compact('packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $packageRequest)
    {
        // dd($packageRequest->all());
        $getInsertedId = $this->package->create($packageRequest);
        // p(lastSql());exit;
        return redirect()->route('package.index')->withInput();    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = $this->package->find($id);
        $package_info = $package->hasManyPackageInfo;

        return view('admin.package.show',compact('package', 'package_info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package      = $this->package->find($id); //套餐详情
        $package_info = $package->hasManyPackageInfo->toJson(); //套餐返还详情

        // dd($package);
        // dd($package_info);
        return view('admin.package.edit', compact('package', 'package_info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $packageRequest, $id)
    {
        /*p($id);
        dd($packageRequest->all());*/
        $this->package->update($packageRequest, $id);
        return redirect()->route('package.index')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        // dd('删了');
        $this->package->destroy($id);        
        return redirect()->route('package.index');
    }

    //ajax判断车型是否重复
    public function checkRepeat(Request $request){

        // dd($request->all());
        if($this->category->isRepeat($request)){
            //车型重复
            return response()->json(array(
                'status' => 1,
                // 'data'   => $category,
                'message'   => '系列名称重复'
            ));
        }else{
            //车型不重复
            return response()->json(array(
                'status' => 0,
                'message'   => '系列名称不重复'
            ));
        }
    }
}
