<?php

    namespace App\Http\Controllers;

    use App\ExportedCharts;
    use App\TrackingSensors;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Date;
    use Illuminate\Support\Facades\Hash;
    use App\Http\Requests\RequestRules\ExperimentNameRule;

    class UserController extends Controller
    {
        private $user;

        public function __construct()
        {
            $this->middleware('auth',[
                'except' => [
                    'getExportedChart',
                ]
            ]);
            $this->user = Auth::user();
        }
         /// only login
        public function getUserProfile()
        {

          return $this->user;
        }
        public function changeUserProfile(Request $request)
        {
           $this->validate($request,[
               'profile' =>'required|array'
           ]);

           $this->user->user_profile = $request->get('profile');
           $this->user->save();
            return response()->json('Profile data will changed '.json_last_error_msg(),200);
        }
        public function changeUserEmail(Request $request){
            $this->validate($request,[
                'email' =>'required|email'
            ]);
            $this->user->email = $request->get('email');
            $this->user->save();
            return response()->json('Email will changed',200);
        }
        public function changeUserName(Request $request){
            $this->validate($request,[
                'name' =>'required|string'
            ]);
            $this->user->name = $request->get('name');
            $this->user->save();
            return response()->json('Email will changed',200);
        }
        public function changeUserPassword(Request $request){
            $this->validate($request,[
                'password'=>['required',function($attribute, $value, $fail){
                 if(!Hash::check($value,Auth::user()->password)){
                     return $fail(__('The current password is incorrect.'));
                 };
                }],
                'new_password'=>'required|min:4'
            ]);
            $this->user->password = Hash::make($request->get('new_password'));
            $this->user->save();
            return response()->json('Password will changed',200);
        }

        public function getUserTrackingSensorsList(){
          return $this->user->trackingSensors;
        }
        public function addSensorToTrackingList(Request $request){
           $this->validate($request,[
               'sensor_id' => ['required','numeric'],
               'experiment_name'=> ['required','string',new ExperimentNameRule],
               'filter_type'=> ['required','string'],
               'filter_value'=> ['required','numeric']
           ]);
           $lastValue = parent::getSenorLastValue(
               $request->get('sensor_id'),
               $request->get('experiment_name')
           )['hodnota'];
           $filter = false;
           switch ($request->get('filter_type')){
               case 'more':{
                   $filter = $lastValue > $request->get('filter_value');
                   break;
               }
               case 'les':{
                   $filter = $lastValue < $request->get('filter_value');
                   break;
               }
           }
           $tmp = new TrackingSensors;
           $tmp->experiment_name = $request->get('experiment_name');
           $tmp->user_id = $this->user->id;
           $tmp->sensor_id = $request->get('sensor_id');
           $tmp->sensor_data = [
               'filter_type' =>$request->get('filter_type'),
               'filter_value' =>$request->get('filter_value'),
               'passed'=>$filter,
                'sensor_value'=>$lastValue,
                'data'=>getSensorById($request->get('sensor_id'),$request->get('experiment_name'),false)
           ];
           $tmp->save();
            return response()->json('Sensor will added into tracking list',200);
        }
        public function removeSensorFromTrackingList(Request $request){
          $this->validate($request,[
              'id'=>['required','numeric']
          ]);
          TrackingSensors::destroy($request->get('id'));
            return response()->json('Tracking will destroyed',200);
        }

        public function getUserExportedChartList(){
            return $this->user->exportedCharts;
        }

        public function addChartToExportsList(Request $request){
                 $this->validate($request,[
                     'chart_data' =>'required'
                 ]);
                 $tmp = new ExportedCharts;
                 $tmp ->user_id = $this->user->id;
                 $tmp->chart_data = $request->get('chart_data');
                 $tmp->url_id = uniqid();
                 $tmp->save();
            return response()->json('Chart will added into exported chart list',200);
        }
        public function removeChartFromExportsList(Request $request){
                $this->validate($request,[
                    'id'=>['required','numeric']
                ]);
                ExportedCharts::destroy($request->get('id'));
                return response()->json('Chart removed.',200);
        }
        ///for all users
        public function getExportedChart(Request $request){
                  $this->validate($request,[
                      'url_id' => 'required'
                  ]);
                  $exported = ExportedCharts::where('url_id','=',$request->get('url_id'))->first();
                  $exported->user->get();
                  return [
                       'exported'=>$exported
                  ];
        }
        //****
    }
