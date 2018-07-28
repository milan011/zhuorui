<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;

class HomeController extends Controller {

	/*protected $transcation;
		    protected $plan;
		    protected $car;
		    protected $want;
		    protected $chance;
	*/

	/*public function __construct(
		        PlanRepositoryContract $plan,
		        CarRepositoryContract $car,
		        WantRepositoryContract $want,
		        ChanceRepositoryContract $chance,
		        TranscationRepositoryContract $transcation,
		        NoticeRepositoryContract $notice
		    ) {
		        $this->plan        = $plan;
		        $this->car         = $car;
		        $this->want        = $want;
		        $this->chance      = $chance;
		        $this->transcation = $transcation;
		        $this->notice      = $notice;
		        // $this->middleware('brand.create', ['only' => ['create']]);
	*/

	public function __construct() {

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		// dd('lai le');
		$user = $request->user();
		// dd($user);
		/*$cars_num = $this->car->getAllCars($request, true)->toArray()['total'];
			        $wants_num = $this->want->getAllWants($request, true)->toArray()['total'];
			        $request['participate'] = false;
			        $transcations_num = $this->transcation->getAllTranscations($request)->toArray()['total'];
			        $plans_num = $this->plan->getAllPlans($request)->toArray()['total'];
			        $chances_num = $this->chance->getAllChances($request)->toArray()['total'];

			        $request['need_follow'] = getExpiredDate();
			        $request['car_status']  = '1';
			        $request['want_status'] = '1';
			        $need_follow_cars       = $this->car->getAllCars($request, true); //待跟进车源
			        // dd(lastSql());
			        $need_follow_wants      = $this->want->getAllWants($request, true); //待跟进客源

		*/
		// dd(lastSql());
		// dd($notice);
		// dd(lastSql());
		// dd($need_follow_wants);
		// dd($threeDaysAgo = getExpiredDate());
		/*p(lastSql());
        p($user['attributes']);exit;*/
		/*p($cars->toArray()['total']);
			        p($wants->toArray()['total']);
			        p($transcations->toArray()['total']);
			        p($plans->toArray()['total']);
		*/

		return view('admin.home.index');

		/*return view('admin.home.index', compact(
			            'cars_num', 'wants_num', 'transcations_num', 'plans_num', 'chances_num','need_follow_cars','need_follow_wants','notice'
		*/
	}
}
