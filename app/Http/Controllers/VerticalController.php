<?php

namespace App\Http\Controllers;

use App\Models\Vertical;
use App\Models\VerticalCategory;
use App\Models\FrontendSectionsStatusses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class VerticalController extends Controller
{

    // single post

    public function post($slug){
        $userType = Auth::user()?->type;
        $post = Vertical::where('slug', $slug)->first();

        // Check post status
        if ( isset($post->status) && ! $post->status && $userType !== "admin") {
            abort(404);
        }

        $previousPost = Vertical::where('id', '<', $post->id)
                        ->where('status', true)
                        ->orderByDesc('id')
                        ->first();

        $nextPost = Vertical::where('id', '>', $post->id)
                        ->where('status', true)
                        ->orderBy('id')
                        ->first();

        $relatedPosts = Vertical::whereHas('category', function ($query) use ($slug) {
                $query->where('name', 'like', "%{$slug}%");
            })->where('id', '!=', $post->id)
            ->where('status', true)
            ->where(function ($query) use ($post) {
                $tags = explode(',', $post->tag);
                foreach ($tags as $tag) {
                    $query->orWhere('tag', 'LIKE', '%' . $tag . '%');
                }
            })
            ->orderByDesc('id')
            ->take(2)
            ->get();

        if ($post) {
            return view('vertical.post', compact('post', 'previousPost', 'nextPost', 'relatedPosts'));
        } else {
            abort(404);
        }
    }


    // archive pages
    
    public function index(){

        $fSecSettings = FrontendSectionsStatusses::first();
        $posts_per_page = $fSecSettings->vertical_a_posts_per_page;

        $posts = Vertical::where('status', 1)->orderBy('id', 'desc')->paginate($posts_per_page);
        $hero = [
            'type' => 'vertical',
            'title' => __($fSecSettings->vertical_a_title),
            'subtitle' => __($fSecSettings->vertical_a_subtitle),
            'description' => __($fSecSettings->vertical_a_description)
        ];
        return view('vertical.index', compact('posts', 'hero'));
    }
    
    public function tags($slug){

        $fSecSettings = FrontendSectionsStatusses::first();
        $posts_per_page = $fSecSettings->vertical_a_posts_per_page;

        $posts = Vertical::where('tag', 'like', "%{$slug}%")->where('status', 1)->orderBy('id', 'desc')->paginate($posts_per_page);
        $hero = [
            'type' => 'tag',
            'title' => $slug,
            'subtitle' => __('Tag Archive'),
            'description' => __($fSecSettings->vertical_a_description)
        ];

        if ($posts->isEmpty()) {
            abort(404);
        }

        return view('vertical.index', compact('posts', 'hero'));
    }
    
    public function categories($slug){

        $fSecSettings = FrontendSectionsStatusses::first();
        $posts_per_page = $fSecSettings->vertical_a_posts_per_page;

        $posts = Vertical::whereHas('category', function ($query) use ($slug) {
                    $query->where('name', 'like', "%{$slug}%");
                })->where('status', 1)->orderBy('id', 'desc')->paginate($posts_per_page);
        $hero = [
            'type' => 'category',
            'title' => $slug,
            'subtitle' => __('Category Archive'),
            'description' => __($fSecSettings->vertical_a_description)
        ];

        if ($posts->isEmpty()) {
            abort(404);
        }

        return view('vertical.index', compact('posts', 'hero'));
    }
    
    public function author($user_id){

        $fSecSettings = FrontendSectionsStatusses::first();
        $posts_per_page = $fSecSettings->vertical_a_posts_per_page;

        $posts = Vertical::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'desc')->paginate($posts_per_page);
        $hero = [
            'type' => 'author',
            'title' => $user_id,
            'subtitle' => 'Author Archive',
            'description' => __($fSecSettings->vertical_a_description)
        ];

        if ($posts->isEmpty()) {
            abort(404);
        }

        return view('vertical.index', compact('posts', 'hero'));
    }

    // dashboard

    public function verticalList(){
        $list = Vertical::orderBy('id', 'desc')->get();
        return view('panel.vertical.list', compact('list'));
    }

    public function verticalAddOrUpdate($id = null){
        if ($id == null){
            $vertical = null;
        }else{
            $vertical = Vertical::where('id', $id)->firstOrFail();
        }
        $verticalCategory = VerticalCategory::all();

        return view('panel.vertical.form', compact('vertical', 'verticalCategory'));
    }

    public function verticalDelete($id = null){
        $post = Vertical::where('id', $id)->firstOrFail();
        $post->delete();
        return back()->with(['message' => 'Deleted Successfully', 'type' => 'success']);
    }

    public function verticalAddOrUpdateSave(Request $request){

        if ($request->vertical_id != 'undefined'){
            $post = Vertical::where('id', $request->vertical_id)->firstOrFail();
        } else {
            $post = new Vertical();
        }

        if ($request->hasFile('feature_image')) {
            $path = 'upload/images/vertical/';
            $image = $request->file('feature_image');
            $image_name = Str::random(4) . '-' . Str::slug($request->slug) . '.' . $image->getClientOriginalExtension();

            //Resim uzantı kontrolü
            $imageTypes = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
            if (!in_array(Str::lower($image->getClientOriginalExtension()), $imageTypes)) {
                $data = array(
                    'errors' => ['The file extension must be jpg, jpeg, png, webp or svg.'],
                );
                return response()->json($data, 419);
            }

            $image->move($path, $image_name);

            $feature_image = $path . $image_name;
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->feature_image = $feature_image ?? $post->feature_image;
        $post->slug = Str::slug($request->slug);
        $post->seo_title = $request->seo_title;
        $post->seo_description = $request->seo_description;
        $post->category_id = $request->category;
        $post->tag = $request->tag;
        $post->status = $request->status;
        $post->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $post->save();
    }

    public function verticalCategoryList(Request $request) {
        $list = VerticalCategory::orderBy('updated_at', 'desc')->get();
        return view('panel.vertical.category.list', compact('list'));
    }

    
    public function verticalCategoryAddOrUpdate($id = null){
        if ($id == null){
            $category = null;
        }else{
            $category = VerticalCategory::where('id', $id)->firstOrFail();
        }
        $list = VerticalCategory::orderBy('updated_at', 'desc')->get();
        return view('panel.vertical.category.form', compact('list', 'category'));
    }

    
    public function verticalCategoryAddOrUpdateSave(Request $request){

        if ($request->category_id != 'undefined'){
            $post = VerticalCategory::where('id', $request->category_id)->firstOrFail();
        } else {
            $post = new VerticalCategory();
        }

        if ($request->hasFile('icon')) {
            $path = 'upload/images/vertical/category/';
            $image = $request->file('icon');
            $image_name = Str::random(4) . '-' . Str::slug($request->slug) . '.' . $image->getClientOriginalExtension();

            //Resim uzantı kontrolü
            $imageTypes = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
            if (!in_array(Str::lower($image->getClientOriginalExtension()), $imageTypes)) {
                $data = array(
                    'errors' => ['The file extension must be jpg, jpeg, png, webp or svg.'],
                );
                return response()->json($data, 419);
            }

            $image->move($path, $image_name);

            $feature_image = $path . $image_name;
        }

        $post->name = $request->name;
        $post->slug = Str::slug($request->slug);
        $post->icon = $feature_image ?? $post->icon;
        $post->save();
    }

    public function panelVerticalList(Request $request){
        try {
            $name = $request->name;
            $url = "https://platodata.network/aiwire/$name/feed/";

            // $response = Http::get($url);

            // Parse XML data
            $xml = simplexml_load_file( $url, 'SimpleXMLElement', LIBXML_NOCDATA );

            $collection = collect(json_decode(json_encode($xml), true));

            $vertical = explode(' – ', $collection['channel']['title']);


            // Extract 'item' array from the XML
            $items = collect($collection['channel']['item']);

            $items = $items->map(function ($item, $key) use( $vertical ){
                $filteredContent = $this->removePTagsWithSpecificChildren( $item['description'] );
                $item['image'] = $this->extractImagesFromDescription( $filteredContent );
                $item['path'] = $this->getLastSegemntFromUrl( $item['link'] );
                $item['description'] = str_replace("\n\n", "<br><br>", $filteredContent );
                $item['vertical_name'] = $vertical[0];
                $item['vertical_title'] = $vertical[1];
                $item['sequence'] = $key + 1;
                return $item;
            });

            // If request is coming for crawling then we don't need to do paginate we will sent all data at once.
            if( $request->crawling )
            {
                $path = $items->map(function ($item, $key) use( $name ){
                    return route( 'dashboard.vertical.single', [ 'name' => $name, 'path' => $this->getLastSegemntFromUrl($item['link']) ] ); // Assuming getLastSegemntFromUrl() returns only the path
                });
                return response()->json([ 'data' => $path ]);
            }

            // Paginate the 'item' array
            $perPage = 10; // Number of items per page
            $currentPage = $request->get('page') ?: 1;
            $pagedItems = new Paginator(
                $items->forPage($currentPage, $perPage),
                $perPage,
                $currentPage
            );


            // If request is ajax then response in json format
            if( $request->ajax() )
            {
                return [
                    'data' => $pagedItems,
                    'name' => $name
                ];
                
            }else{
                return view('panel.vertical.content', ['data' => $pagedItems, 'name' => $name, 'vertical_name' => $vertical[0], 'vertical_title' => $vertical[1]  ]);
            }
      

        } catch (\Exception $e) {
            abort(404); // Display 404 page if an exception occurs
        }
    }

    public function panelVerticalSingle(Request $request){
        try {
            $name = $request->name;
            $path = $request->path;

            $url = "https://platodata.network/aiwire/$name/feed/";

            // Parse XML data
            $xml = simplexml_load_file( $url, 'SimpleXMLElement', LIBXML_NOCDATA );

            $collection = collect(json_decode(json_encode($xml), true));

            $vertical = explode(' – ', $collection['channel']['title']);

            // Extract 'item' array from the XML
            $items = collect($collection['channel']['item']);

            $filteredItems = $items->filter(function ($item, $key) use ($path) {
                if ( isset($item['link'] ) ) {
                    $lastSegment = basename( parse_url( rtrim($item['link'], '/' ), PHP_URL_PATH) );
                    return $lastSegment === $path;
                }
                return false;
            });

            
            $pagedItemKey = $filteredItems->keys()->toArray();
            $pagedItems = collect( $filteredItems->first() );

            $filteredContent = $this->removePTagsWithSpecificChildren( $pagedItems['description'] );
            $pagedItems['sequence'] = $pagedItemKey[0] + 1;
            $pagedItems['path'] = $this->getLastSegemntFromUrl( $pagedItems['link'] );
            $pagedItems['description'] = str_replace("\n\n", "<br><br>", $filteredContent );
            $pagedItems['excerpt'] = \Str::limit( strip_tags( $filteredContent ), 200 );
            $pagedItems['media'] = $this->extractImagesFromDescription( $pagedItems['description'])[0];


            return view('panel.vertical.single', ['data' => $pagedItems, 'name' => $name, 'vertical_name' => $vertical[0], 'vertical_title' => $vertical[1]  ]);

        } catch (\Exception $e) {
            abort(404); // Display 404 page if an exception occurs
        }
    }
    
    private function removePTagsWithSpecificChildren($html) {
        $doc = new \DOMDocument();
        // Load HTML content
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    
        $xpath = new \DOMXPath($doc);
    
        // Find all <p> tags
        $paragraphs = $xpath->query('//p');
    
        foreach ($paragraphs as $p) {
            $hasImageOrHeaderOrList = false;
    
            // Check if <p> contains <img>, <h1>, or <ul>
            foreach ($p->childNodes as $child) {
                if ($child->nodeName === 'img' || $child->nodeName === 'h1' || $child->nodeName === 'ul') {
                    $hasImageOrHeaderOrList = true;
                    break;
                }
            }
    
            // Remove <p> if it contains <img>, <h1>, or <ul>
            if ($hasImageOrHeaderOrList) {
                while ($p->firstChild) {
                    $p->parentNode->insertBefore($p->firstChild, $p);
                }
                $p->parentNode->removeChild($p);
            }
        }
    
        // Get the updated HTML content
        $updatedHtml = $doc->saveHTML();
    
        // Remove doctype, <html>, and <body> tags from the string
        $updatedHtml = preg_replace(array('~<!DOCTYPE[^>]*>~', '~<html[^>]*>~', '~</html>~', '~<body[^>]*>~', '~</body>~'), '', $updatedHtml);
    
        return $updatedHtml;
    }

    private function extractImagesFromDescription($items)
    {
        $images = [];

        $description = $items ?? '';
    
        // Wrap the content in a basic HTML structure
        $contentWithHtml = '<!DOCTYPE html><html><body>' . $description . '</body></html>';
    
        // Create a new DOMDocument
        $doc = new \DOMDocument();
    
        // Load the HTML content
        @$doc->loadHTML(mb_convert_encoding($contentWithHtml, 'HTML-ENTITIES', 'UTF-8'));
    
        // Get all img tags from the content
        $imgTags = $doc->getElementsByTagName('img');
    
        // Extract src attribute of each img tag
        foreach ($imgTags as $imgTag) {
            $images[] = $imgTag->getAttribute('src');
        }
    
        return $images;
      
    }

    private function segmentTextIntoPTags($html) {
        $doc = new DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
        // Extract text content from the single <p> tag
        $textContent = $doc->getElementsByTagName('p')->item(0)->textContent;
    
        // Split text content by line breaks into an array
        $paragraphs = explode("\n", $textContent);
    
        // Create a new <p> tag for each segment of text
        $newHtml = '';
        foreach ($paragraphs as $paragraph) {
            // Trim each paragraph to remove leading/trailing spaces
            $trimmedParagraph = trim($paragraph);
    
            // Create a new <p> tag if the paragraph is not empty
            if (!empty($trimmedParagraph)) {
                $newHtml .= "<p>$trimmedParagraph</p>";
            }
        }
    
        return $newHtml;
    }

    private function getLastSegemntFromUrl($link)
    {
        $parsedUrl = parse_url( rtrim($link, '/') );
        if (isset($parsedUrl['path'])) {
            // Get the path segment
            $pathSegments = explode('/', $parsedUrl['path']);
            // Extract the last segment (slug)
            $lastSegment = end($pathSegments);
        
            return $lastSegment;
        }
    }
}