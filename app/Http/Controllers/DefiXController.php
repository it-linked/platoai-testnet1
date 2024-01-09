<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\DefiX;

class DefiXController extends Controller
{


    public function viewDefixPage($slug=null, $sub_slug=null){
        $defix = Defix::where('slug', $sub_slug)->first();

        return view('panel.defixgateway.index', compact('defix'));

    }

    public function fetchDataFromTable()
    {
        // Retrieve the parent records where parent_id is null
        $parents = DefiX::whereNull("parent_id")->where("id",">",620)->get();
        foreach ($parents as $parent) {
            // Fetch the external_link using cURL
            // $externalLink = $this->fetchExternalLink($parent->external_link);

            // Update the parent record with the fetched external_link
           // $parent->update(['external_link' => $externalLink]);

            // Fetch and update the external_link for child records
            $this->fetchAndUpdateChildExternalLinks($parent->id);
        }
    }

    protected function fetchExternalLink($url)
{
    try {
        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing)
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        // Execute cURL session and fetch the content
        $html = curl_exec($ch);
       
        // Check for cURL errors
        // if (curl_errno($ch)) {
        //     throw new \Exception('Curl error: '.$url ."==" . curl_error($ch));
        // }

        // Close cURL session
        curl_close($ch);

        // Use the Symfony DomCrawler to parse the HTML and extract the external_link
        $crawler = new Crawler($html);
        $externalLink = $crawler->filter('iframe')->attr('src');

        return $externalLink;
    } catch (\Exception $e) {
        // Handle the exception (e.g., log the error, return a default value, etc.)
        echo 'Error fetching external link: '.$url."===" . $e->getMessage() . "<br>";
        return $url; // Return null or a default value in case of an error
    }
}


    protected function fetchAndUpdateChildExternalLinks($parentId)
    {
        //echo $parentId."<br>";
        // Retrieve child records for the given parent_id
        $children = DefiX::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            // Fetch the external_link using cURL
            if (Str::contains($child->external_link, 'platoaistream.com')) {
                // Fetch the external_link using cURL
                $externalLink = $this->fetchExternalLink($child->external_link);
               
                // Update the child record with the fetched external_link
                $child->update(['external_link' => $externalLink]);
                echo '<script>window.location.reload();</script>';
                exit();

            } else {
                // If external link is not from the specified domain, you can handle it accordingly
                echo "Skipping fetching for external link outside platoaistream.com domain: " . $child->external_link . "<br>";
            }
        }
    }
    public function index(Request $request, $parent_id = null)
    {
        // $this->fetchDataFromTable();
        // exit();
        // // Target URL
        // $url = "https://platoaistream.com/";

        // // Initialize cURL session
        // $ch = curl_init($url);

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the transfer as a string
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (use only for testing)

        // // Execute cURL session and fetch the content
        // $html = curl_exec($ch);

        // // Check for cURL errors
        // if (curl_errno($ch)) {
        //     echo 'Curl error: ' . curl_error($ch);
        //     exit();
        // }

        // // Close cURL session
        // curl_close($ch);

        // // Use the SimpleHTMLDom library to parse the HTML
        // $crawler = new Crawler($html);

        // $ulElement = $crawler->filter('li#menu-item-1873884 > ul')->first();

        // if ($ulElement) {
        //     $liElements = $ulElement->children('li');

        //     $liElements->each(function (Crawler $li) {
        //         // Find <a> tag inside each <li>
        //         $aElement = $li->filter('a')->first();

        //         // Display the found <a> tag content
        //         if ($aElement) {



        //             $innerUlElement = $li->filter('ul')->first();

        //             // Display the found inner <ul> content
        //             if ($innerUlElement) {
        //                 $title = $aElement->text();
        //             $slug = Str::slug($title);


        //             // Create a new publication
        //             $defix = DefiX::create([
        //                 'title' => $title,
        //                 'slug' => $slug,
        //                 'external_link' => "#",
        //                 'status' => 1 // Default to 1 if not provided
        //             ]);
        //             $parent_id = $defix->id;
        //                 // Loop through <a> tags inside the inner <ul> to get href attributes
        //                 $aElements = $innerUlElement->filter('a');
        //                 $aElements->each(function (Crawler $a)  use ($parent_id){
        //                     // Get and display the href attribute
        //                     $href = $a->attr('href');
        //                     $title = $a->innerText();
        //                     $slug = Str::slug($title);

        //                     DefiX::create([
        //                         'title' => $title,
        //                         'slug' => $slug,
        //                         'external_link' => $href,
        //                         'parent_id'=>$parent_id,
        //                         'status' => 1 // Default to 1 if not provided
        //                     ]);

        //                 });
        //             } else {
        //                 echo "Inner UL not found inside LI.\n";
        //             }
        //         } else {
        //             echo "A tag not found inside LI.\n";
        //         }
        //     });
        // } else {
        //     echo "First UL inside LI not found.";
        // }


        // exit();

        // Create a query to fetch DefiX entries
        $query = DefiX::orderBy('title', 'asc');
       
        // Check if there is a parent_id
        if ($parent_id !== null) {
            // If parent_id is provided, filter entries by parent_id
            $query->where('parent_id', $parent_id);
        } else
            $query->where('parent_id', null);


        // Check if there is a search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%$search%");
        }

        // Paginate the results
        $list = $query->paginate(20);

        // Pass the results to the view
        return view('panel.admin.defixgateway.index', compact('list', 'parent_id'));
    }




    public function create($parent_id = null)
    {
        return view('panel.admin.defixgateway.DefiXNewOrEdit', compact('parent_id'));
    }

    public function edit($id, $parent_id = null)
    {
        $defix = Defix::where('id', $id)->first();

        if ($parent_id == null) {
            return view('panel.admin.defixgateway.DefiXNewOrEdit', compact('defix'));
        } else {
            return view('panel.admin.defixgateway.DefiXNewOrEdit', compact('defix', 'parent_id'));
        }

    }


    public function store(Request $request)
    {
        try{
        $request->validate([
            'title' => 'required|string',
            'slug' => 'nullable',
            'external_link' => 'nullable|url',
            'status' => 'nullable|boolean',
            'parent_id' => 'nullable|exists:defix_gateways,id', // Validation for the parent_id
        ]);

        $slug = Str::slug($request->input('title'));

        if (isset($request->id) and $request->id) {
            $defix = DefiX::findOrFail($request->input('id'));
            // Update the existing publication
            $defix->update([
                'title' => $request->input('title'),
                'slug' => $slug,
                'external_link' => $request->input('external_link'),
                'status' => $request->input('status', 1), // Default to 1 if not provided
            ]);

        } else {
            // Create a new publication
            DefiX::create([
                'title' => $request->input('title'),
                'slug' => $slug,
                'external_link' => $request->input('external_link'),
                'status' => $request->input('status', 1), // Default to 1 if not provided
                'parent_id'=>$request->parent_id
            ]);

        }} catch (\Exception $e) {
            // Log the error
            // \Log::error('Error in store method: ' . $e->getMessage());
        
            // Optionally, return a response with an error message
            return response()->json(['error' => 'An error occurred.'. $e->getMessage()], 500);
        }

        return response()->json(['id' => $request->id,'parent_id'=>$request->parent_id], 200);

    }


    public function delete($id)
    {
        $publication = DefiX::where('id', $id)->first();
        $publication->delete();
        return back()->with(['message' => 'Publication is deleted.', 'type' => 'success']);
    }
    public function updateStatus($id)
    {
        $publication = DefiX::find($id);

        // Toggle the status (switch between 0 and 1)
        $publication->status = !$publication->status;
        $publication->save();
        // Determine the message based on the current state
        $message = $publication->status ? 'Publication enabled successfully!' : 'Publication disabled successfully!';

        // Redirect with the appropriate success message
        return back()->with(['message' => $message, 'type' => 'success']);
    }
}
