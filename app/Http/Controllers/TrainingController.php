<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use League\CommonMark\MarkdownConverterInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Training;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TrainingController extends Controller
{
    protected $converter;

    public function __construct(MarkdownConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function index()
    {
        $courses = Training::get();

        return view('training.index', compact('courses'));
    }

    public function show($id)
    {
        $course = Training::find($id);
        //$markdown = Storage::disk('local')->get("markdown/" . $course->markdown_slug);
        

        return view('training.show', compact('course'));
    }

    public function create()
    {
        return view('training.create');
    }

    public function store(Request $request)
    {

        $slug = $request->coursename . date('d-m-Yh-i-s') . ".md";
        $slug = str_replace(' ', '-',$request->name);
        $slug = str_replace("'", '',$slug);
        $slug = str_replace("`", '', $slug);
        $slug = str_replace("’", '', $slug);
        $slug = str_replace("‘", '', $slug);
        $slug = str_replace("®", '', $slug);
        $slug = strtolower($slug);
        $course = new Training([
            'creator_id' => Auth::id(),
            'name' => $request->coursename,
            'markdown_slug' => $slug,
            'markdown' => $request->markdown,
        ]);
        
        $course->save();

        //$markdown = Storage::disk('local')->put('markdown/' . $slug, $request->markdown);
        return redirect()->route('training.index')->with('success', 'Course has been created.');

    }

    public function edit($id)
    {
        $course = Training::find($id);
        //$markdown = Storage::disk('local')->get("markdown/" . $course->markdown_slug);

        return view('training.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Training::find($id);
        $slug = $course->markdown_slug;
        //$deletefile = Storage::disk('local')->delete('markdown/' . $slug);
        //$markdown = Storage::disk('local')->put('markdown/' . $slug, $request->markdown);

        $course->update([
            'name' => $request->coursename,
            'markdown' => $request->markdown
            ]);
        $course->save();

        return redirect()->route('training.index')->with('success', 'Course has been successfully updated.');
    }

}
