<?php

namespace App\Http\Controllers;

use App\Models\Vertical;
use App\Models\VerticalCategory;
use App\Models\FrontendSectionsStatusses;
use App\Models\Sitemap;
use App\Models\SitemapPages;
use App\Models\VerticalScheduling;
use App\Events\SitemapGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use GuzzleHttp\Client;

class VerticalController extends Controller
{

    // single post

    public function post( $category, $slug ){
        $userType = Auth::user()->type;
        $post = Vertical::where('slug', $slug)->whereHas('category', function($query) use ($category){
            $query->where( 'slug', $category );
        })->first();

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
                    $query->where('slug', 'like', "%{$slug}%");
                })->where('status', 1)->orderBy('id', 'desc')->paginate($posts_per_page);
        $hero = [
            'type' => 'category',
            'title' => ucwords( str_replace('-', ' ', $slug) ),
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
        $list = Vertical::orderBy('id', 'desc')->paginate(10);
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

    public function verticalCrawlList(){
        $groupBy = Sitemap::orderBy('group_by', 'ASC')->pluck('group_by', 'id');
        $list = VerticalScheduling::with('group')->orderBy('id', 'desc')->paginate(10);
        $paths = [
            [
                'name' => 'AI',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'artificial-intelligence' ])
            ],
            [
                'name' => 'AR/VR',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'ar-vr' ])
            ],
            [
                'name' => 'Big Data',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'big-data' ])
            ],
            [
                'name' => 'Blockchain',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'blockchain' ])
            ],
            [
                'name' => 'Carbon',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'carbon' ])
            ],
            [
                'name' => 'Cleantech',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'cleantech' ])
            ],
            [
                'name' => 'Code',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'code' ])
            ],
            [
                'name' => 'Crowdfunding',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'crowdfunding' ])
            ],
            [
                'name' => 'Cyber Security',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'cyber-securitye' ])
            ],
            [
                'name' => 'Esports',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'esports' ])
            ],
            [
                'name' => 'Gaming',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'gaming' ])
            ],
            [
                'name' => 'NFTs',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'nfts' ])
            ],
            [
                'name' => 'Payments',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'payments' ])
            ],
            [
                'name' => 'Quantum',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'quantum' ])
            ],
            [
                'name' => 'SEO',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'seo' ])
            ],
            [
                'name' => 'Startups',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'startups' ])
            ],
            [
                'name' => 'Trading',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'trading' ])
            ],
            [
                'name' => 'Venture Capital',
                'path' => route('dashboard.vertical.feed', [ 'slug' => 'venture-capital' ])
            ]
        ];
        return view('panel.vertical.crawl.list', compact('list', 'groupBy', 'paths'));
    }

    /**
     * This will resume vertical cron scheduling
     */
    public function verticalCrawlResume(Request $request){
        $id = $request->schedule_id;
        VerticalScheduling::where('id', $id)->update(['status' => 'running']);

        return response()->json(['message' => 'Vertical scheduling resume successfully.']);
    }

    /**
     * This will stop vertical cron scheduling
     */
    public function verticalCrawlStop(Request $request){
        $id = $request->schedule_id;
        VerticalScheduling::where('id', $id)->update(['status' => 'stop']);

        return response()->json(['message' => 'Vertical scheduling stop successfully.']);
    }

    /**
     * This will stop vertical cron scheduling
     */
    public function verticalCrawlRunAgain(Request $request){
        $id = $request->schedule_id;
        VerticalScheduling::where('id', $id)->update(['status' => 'running']);

        return response()->json(['message' => 'Vertical scheduling started successfully.']);
    }

    /**
     * This will instantly crawl the verticals from the given url
     * also it will create sitemap and create entry to vertical_scheduling
     */
    public function verticalCrawlScrape(Request $request){
        $url = $request->source_url;
        $group_by = $request->group_by;
        $frequency = $request->frequency;
        $priority = $request->priority;
        try {

            // Parse XML data
            $xml = simplexml_load_file( $url, 'SimpleXMLElement', LIBXML_NOCDATA );

            $collection = collect(json_decode(json_encode($xml), true));

            $vertical = explode(' – ', $collection['channel']['title']);

            $category_name = $vertical[0];
            $category_slug = getUrlPart($url, 1);

            $category = VerticalCategory::updateOrCreate(
                [ 'name' => $category_name, 'slug' => $category_slug ],
                [ 'name' => $category_name, 'slug' => $category_slug ]
            );

            // Extract 'item' array from the XML
            $items = collect($collection['channel']['item']);

            $items->map(function ($item, $key) use( $category, $group_by, $frequency, $priority ){
                $filteredContent = $this->removePTagsWithSpecificChildren( $item['description'] );
                $images =  $this->extractImagesFromDescription( $filteredContent );
                $image = $this->downloadImage( $images );
                $path = $this->getLastSegemntFromUrl( $item['link'] );
                $description = nl2br( $filteredContent );
                $contentWithoutImage = $this->removeImgTags($description);

                $dateTime = \DateTime::createFromFormat('D, d M Y H:i:s O', $item['pubDate'] );
                Vertical::updateOrCreate(
                    [ 'title' => $item['title'], 'slug' => $path ],
                    [
                        'title' => $item['title'], 
                        'content' => $contentWithoutImage, 
                        'feature_image' => $image[0] ?? $image[0], 
                        'slug' => $path,
                        'seo_title' =>  $item['title'],
                        'category_id' => $category->id,
                        'status' => 1,
                        'user_id' => \Auth::user()->id,
                        'updated_at' => $dateTime->format('Y-m-d H:i:s')
                    ]
                );

                // Generate sitemap urls for newly added vertical
                SitemapPages::updateOrCreate(
                    ['url' => url( route( 'vertical.single', [ 'category' => $category->slug, 'slug' => $path ] ) )],
                    [
                        'sitemap_id' => $group_by,
                        'frequency' => $frequency,
                        'priority' => $priority,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );

            });

            // Generate Sitemap
            event(new SitemapGenerate());

            VerticalScheduling::create([
                'group_id' => $group_by,
                'source_link' => $url,
                'cron_interval' => 'onetime',
                'status' => 'stop'
            ]);

            return response()->json([ 'message' => "All Verticals for $category_name has been published!" ]);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage() ],419);
        }
    }

    public function verticalFeed(Request $request){
        try {
            $name = $request->slug;
            $url = "https://platodata.network/aiwire/$name/feed/";

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

            $path = $items->map(function ($item, $key) use( $name ){
                return route( 'vertical.single', [ 'slug' => $this->getLastSegemntFromUrl($item['link']) ] ); // Assuming getLastSegemntFromUrl() returns only the path
            });

            return response()->json([ 'data' => $path ]);

        } catch (\Exception $e) {
            abort(404); // Display 404 page if an exception occurs
        }
    }

    public function panelVerticalList(Request $request){

        $verticalCategory = VerticalCategory::where('slug', $request->slug)->firstOrFail();
        $verticals = Vertical::where('category_id', $verticalCategory->id )->paginate(10);

        if ($verticals->isEmpty()) {
           abort(404);
        }

        if( $request->ajax() )
        {
            return response()->json( [ 'data' => $verticals, 'verticalCategory' => $verticalCategory ], 200 );
        }
        return view('panel.vertical.content', [ 'data' => $verticals, 'verticalCategory' => $verticalCategory ]);

    }

    public function panelVerticalSingle(Request $request){
        $vertical = Vertical::where('slug', $request->path )->with('category')->firstOrFail();
        $relatedVertical = Vertical::orderBy('updated_at', "DESC")->where('id', '!=', $vertical->id)->where('category_id', $vertical->category->id )->limit(4)->get(['id', 'title', 'slug', 'updated_at']);
        return view( 'panel.vertical.single', [ 'vertical' => $vertical, 'relatedVertical' => $relatedVertical ] );
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

    /**
     * This function will remove img from content
     */
    private function removeImgTags($html) {
        $doc = new \DOMDocument();
        // Load HTML content
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    
        $xpath = new \DOMXPath($doc);
    
        // Find all <img> tags
        $images = $xpath->query('//img');
    
        foreach ($images as $img) {
            // Remove the <img> tag
            $img->parentNode->removeChild($img);
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

    private function downloadImage($urls)
    {
        $url_path = [];
        foreach ($urls as $key => $url) {
            $path = 'upload/images/vertical/';
            $client = new Client();
            try {
                $response = $client->get($url);
                $contentType = explode('/', $response->getHeader('Content-Type')[0])[1];
                $imageTypes = ['jpg', 'jpeg', 'png', 'svg', 'webp']; // Allowed image types

                if (!in_array($contentType, $imageTypes)) {
                    $data = array(
                        'errors' => ['The file extension must be jpg, jpeg, png, webp, or svg.'],
                    );
                    return response()->json($data, 419);
                }

                $image_name = basename(parse_url($url, PHP_URL_PATH));

                file_put_contents( public_path($path . $image_name) , $response->getBody() );

                $img_path = $path . $image_name;
                array_push($url_path, $img_path);

            } catch (\Exception $e) {
                // Handle any exceptions if the image couldn't be fetched or saved
                report($e);
            }
        }

        return $url_path;
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