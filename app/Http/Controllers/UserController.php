<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ItemTable;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{

    public function listView(Request $request){

        $files = User::with('order_item');
        if ($request->start_date && $request->end_date) {
            $files = $files->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->search && !empty($request->search)) {
            $files = $files->where(function($q1)use($request){
                $q1->where('order_id','like', $request->search."%");
                $q1->orWhere('name','like', $request->search."%");
                $q1->orWhere('quantity','like', $request->search."%");
                $q1->orWhere('price','like', $request->search."%");
            });
        }

        $files = $files->orderBy('date', 'desc')->orderBy('order_id', 'desc')->get();
        return view('list', compact('files'));
    }

    public function iteam(Request $request,$order_id)
    {
        $files = User::with('order_item');
        $item = ItemTable::where('order_id', $order_id)->get();
        $html =  view('data',compact('item'))->render();
        return response()->json(['html'=>$html]);
    }

    public function importView()
    {
        return view('import');
    }


    public function import()
    {
        // print_r($request);die;
        $csvFile = storage_path('app/users.csv');
        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Get the CSV header row
        // echo"<pre>"; print_r($header);die;
        while (($row = fgetcsv($file)) !== false) {

            $data = array_combine($header, $row);
            // echo "<pre>";print_r($data);die;
            $existingUser = 0;
            $existingUser = User::where('order_id', $data['order_id'])
                                    ->where('date',$data['date'])
                                    ->where('name',$data['name'])
                                    ->count();

                if($existingUser > 0){
                    $existingUser++;
                    session()->flash('message', 'Duplicate entry found.');
                }
                else
                {
                  $data =   User::firstOrCreate([
                    'order_id' => $data['order_id'],
                    'date' => $data['date'],
                    'name' => $data['name'],
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],

                    ]);
                    session()->flash('message', 'Data Export successfully.');
                }

        }

        fclose($file);
        return redirect('/file-import');
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        DB::table("movements")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"Data Deleted successfully."]);
    }
}
