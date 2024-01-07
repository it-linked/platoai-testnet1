<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use Illuminate\Support\Str;


class PublicationController extends Controller
{
    public function viewPublication($slug){
        $publication = Publication::where('slug', $slug)->first();

        return view('panel.publication.index', compact('publication'));

    }
    public function updateStatus($id){
        $publication = Publication::find($id);
    
        // Toggle the status (switch between 0 and 1)
        $publication->status = !$publication->status;
        $publication->save();
        // Determine the message based on the current state
        $message = $publication->status ? 'Publication enabled successfully!' : 'Publication disabled successfully!';

        // Redirect with the appropriate success message
        return back()->with(['message' => $message, 'type' => 'success']);    
    }
    public function index(Request $request) {
        $query = Publication::orderBy('title', 'asc');
    
        // Check if there is a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%");
        }
    
        $publicationList = $query->paginate(20);
    
        return view('panel.admin.publications.index', compact('publicationList'));
    }
    public function create($id=null)
    {
        
        if ($id == null) {
            return view('panel.admin.publications.PublicationNewOrEdit');
        } else {
            $publication = Publication::where('id', $id)->first();
            return view('panel.admin.publications.PublicationNewOrEdit', compact('publication'));
        }
    }


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'slug' => 'nullable',
        'external_link' => 'required|url',
        'status' => 'nullable|boolean',
    ]);

    $slug = Str::slug($request->input('title'));

        // Additional validation for uniqueness, if needed
        // You might want to check if the generated slug already exists in the database

        // Create the publication with the generated slug
        Publication::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'external_link' => $request->input('external_link'),
            'status' => $request->input('status', 1), // Default to 1 if not provided
        ]);
       

    //return redirect('/publications/create')->with('success', 'Publication created successfully!');
   
}
public function delete($id){
    $publication = Publication::where('id', $id)->first();
    $publication->delete();
    return back()->with(['message' => 'Publication is deleted.', 'type' => 'success']);
}

}
