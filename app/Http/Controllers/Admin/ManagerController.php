<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Carbon;
use App\Area;
use App\Image;
// use App\Goods;
use App\Role;
// use App\GoodsPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;
// use App\Repositories\Brand\BrandRepositoryContract;
// use App\Repositories\Category\CategoryRepositoryContract;
use App\Repositories\Manager\ManagerRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
/*use App\Repositories\Car\CarRepositoryContract;
use App\Repositories\Shop\ShopRepositoryContract;
use App\Http\Requests\Cars\UpdateCarsRequest;
use App\Http\Requests\Cars\StoreCarsRequest;*/

class ManagerController extends Controller
{   
    protected $manager;
    protected $user;


    public function __construct(

        ManagerRepositoryContract $manager,
        UserRepositoryContract $user
    ) {
    
        $this->manager = $manager;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {     
        
        $all_manager = $this->manager->getAllManagers($request);
        // dd($all_manager);
        return view('admin.manager.index', compact('all_manager'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.manager.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $getInsertedId = $this->manager->create($request);
        return redirect('manager/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manager = $this->manager->find($id);

        // dd($cars->hasManyImages()->get());
        return view('admin.manager.show', compact('manager'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manager = $this->manager->find($id);
        // dd($manager);
        return view('admin.manager.edit', compact(
            'manager'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $managerRequest, $id)
    {
        // dd($managerRequest->all());
        $this->manager->update($managerRequest, $id);
        return redirect('manager/index')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->manager->destroy($id);        
        return redirect('manager/index');
    }

}
