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
        $groupBy = Sitemap::orderBy('group_by', 'ASC')->get(['group_by', 'id', 'priority', 'frequency']);
        $list = VerticalScheduling::with('group')->whereNot('cron_interval', 'onetime')->orderBy('id', 'desc')->paginate(10);
        return view('panel.vertical.crawl.list', compact('list', 'groupBy'));
    }

    /**
     * This will add vertical cron scheduling
     */
    public function verticalCrawlSchedule(Request $request){
        $verticalScheduling = new VerticalScheduling();
        $verticalScheduling->group_id = $request->group_by;
        $verticalScheduling->cron_interval = $request->interval;
        $verticalScheduling->source_link = $request->source_url;
        $verticalScheduling->status = 'running';
        $verticalScheduling->save();

        return response()->json(['message' => 'Vertical scheduling registered successfully.']);
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
                $filteredContent = removePTagsWithSpecificChildren( $item['description'] );
                $images =  extractImagesFromDescription( $filteredContent );
                $image = downloadImage( $images );
                $path = getLastSegemntFromUrl( $item['link'] );
                $description = nl2br( $filteredContent );
                $contentWithoutImage = removeImgTags($description);

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


    public function verticalCrawlLog(Request $request){
        $id = $request->id;
        $logPath = storage_path("logs/schedule-$id.log");

        if (\File::exists($logPath)) {
            $logs = \File::get($logPath);
        }else{
            $logs = '';
        }
        return response()->json([ 'message' => $logs ]);

    }

}