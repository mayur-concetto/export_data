<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    
    public function listView(Request $request){
      
        $files = new User;
        if ($request->start_date && $request->end_date) {
            $files = $files->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        if ($request->search && !empty($request->search)) {
            $files = $files->where(function($q1)use($request){
                $q1->where('order_id','like', $request->search."%");
                $q1->orWhere('description','like', $request->search."%");
                $q1->orWhere('client_id','like', $request->search."%");
                $q1->orWhere('quantity','like', $request->search."%");
                $q1->orWhere('price','like', $request->search."%");
            });
        }

        $files = $files->orderBy('date', 'desc')->orderBy('order_id', 'desc')->get();
        return view('list', compact('files'));
    }

    public function importView()
    {
        return view('import');
    }

    public function import()
    {
        $csvFile = storage_path('app/users.csv');
        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Get the CSV header row

        while (($row = fgetcsv($file)) !== false) {
            
            $data = array_combine($header, $row);
            $existingUser = 0;
            $existingUser = User::where('order_id', $data['order_id'])
                                    ->where('date',$data['date'])
                                    ->where('description',$data['description'])
                                    ->count();
                                    
                if($existingUser > 0){
                    $existingUser++;
                    session()->flash('message', 'Duplicate entry found.');
                }
                else
                { 
                    User::firstOrCreate([  
                        'order_id' => $data['order_id'],
                        'date' => $data['date'],
                        'description' => $data['description'],
                        'client_id' => $data['client_id'],
                        'quantity' => $data['quantity'],
                        'price' => $data['price']
                    ]);
                    session()->flash('message', 'Data Export successfully.');
                }
              
        }
        fclose($file);
        return redirect('/file-import');
    }

    public function deleteAll(Request $request)  
    {  
        // echo "hello";die;
        $ids = $request->ids;  
        DB::table("movements")->whereIn('id',explode(",",$ids))->delete();  
        return response()->json(['success'=>"Data Deleted successfully."]);  
    }  
}
