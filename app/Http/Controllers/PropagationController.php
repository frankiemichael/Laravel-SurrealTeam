<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propagation;
class PropagationController extends Controller
{
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
        /* $file = public_path('propcsv.csv');
        $array = $this->csvToArray($file);
        
        for ($i = 0; $i < count($array); $i ++)
        {
            Propagation::firstOrCreate($array[$i]);
        }*/
    }


    public function trereife(Request $request)
    {
        if($request->ajax()){
            $data = Propagation::where('site', 0)->where('name', 'LIKE', '%'.$request->search.'%')->get();
            $data = $data->sortBy('location', SORT_REGULAR, true);
            $data = $data->reverse();    
            return $data;
        }
        $data = Propagation::where('site', 0)->get();
        $data = $data->sortBy('location', SORT_REGULAR, true);
        $data = $data->reverse();
        return view('propagation.trereife', compact('data'));
    }

    public function trewidden(Request $request)
    {
        
        if($request->ajax()){
            $data = Propagation::where('site', 1)->where('name', 'LIKE', '%'.$request->search.'%')->get();
            $data = $data->sortBy('location', SORT_REGULAR, true);
            $data = $data->reverse();    
            return $data;
        }
        $data = Propagation::where('site', 1)->get();
        $data = $data->sortBy('location', SORT_REGULAR, true);
        $data = $data->reverse();
        return view('propagation.trewidden', compact('data'));
    }
    
    public function update(Request $request)
    {
        
        $data = Propagation::where('id', $request->id)->first();
        $data->update([
            $request->column => $request->data,
        ]);
        $data->save();
        return 'Plant updated successfully.';
    }

    public function create(Request $request, $source)
    {
       
        return view('propagation.create', compact('source'));
    }

    public function store(Request $request)
    {
        $data = new Propagation([
            'site' => $request->site,
            'name' => $request->name,
            'location' => $request->location,
            'quantity' => $request->quantity,
            'method' => $request->method,
            'cuttings' => $request->cuttings,
            'notes' => $request->notes,
        ]);
        $data->save();
        if($request->site === '0'){
            return redirect()->route('propagation.trereife')->with('success', 'Plant added to database.');
        }elseif($request->site === '1'){
            return redirect()->route('propagation.trewidden')->with('success', 'Plant added to database.');
        }

    }
    
    public function delete(Request $request)
    {
        Propagation::where('id', $request->id)->delete();
        return "Plant successfully deleted.";
    }
}
