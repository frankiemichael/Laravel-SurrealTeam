<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        
        return view('calendar.index');
    }

    public function json(Request $request)
    {   
        
        $data = Event::whereDate('start', '>=', $request->start)->whereDate('end', '<=', $request->end)->get(['id', 'title', 'start', 'end']);
        return response()->json($data);
    }

    public function action(Request $request)
    {

        if($request->ajax()){
            if($request->type == 'add'){
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end
                ]);

                return response()->json($event);
            }
        }
    }
    
    public function editevent(Request $request)
    {
        if($request->ajax()){
            $event = Event::find($request->id);
            $event->title = $request->title;
            $event->start = $request->start;
            $event->end = $request->end;
            $event->save();
            
        }
    }
    public function deleteevent(Request $request)
    {
        if($request->ajax()){
            $event = Event::find($request->id);
            $event->delete();
            return redirect()->route('calendar.index')->with('success', 'Event successfully deleted.');
        }
    }

}
