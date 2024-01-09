<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OpenAIGenerator;
use App\Models\OpenaiGeneratorChatCategory;
use App\Models\UserOpenai;
use App\Models\VerticalCategory;
use App\Models\Blog;
use App\Models\Publication;
use App\Models\DefiX;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        $word = $request->search;
        $result = '';

        if ($word == 'delete'){
            $template_search = [];
            $workbook_search = [];
            $ai_chat_search = [];
            $verical_search = [];
            $blog_search = [];  
            $defix_search = [];
        }else {
            $template_search = OpenAIGenerator::where('title', 'like', "%$word%")->get();

            $workbook_search = UserOpenai::where('title', 'like', "%$word%")->get();

            $ai_chat_search = OpenaiGeneratorChatCategory::where('name', 'like', "%$word%")->orWhere('description', 'like', "%$word%")->get();

            $verical_search = VerticalCategory::where('name', 'like', "%$word%")->orWhere('slug', 'like', "%$word%")->get();

            $blog_search = Blog::where('title', 'like', "%$word%")->orWhere('content', 'like', "%$word%")->get();

            $publication_search = Publication::where('title', 'like', "%$word%")->Where('status', '=', "1")->get();

            $defix_search = Defix::where('title', 'like', "%$word%")->WhereNotNull('parent_id')->get();
            if (count($template_search)==0 and count($workbook_search)==0 and count($ai_chat_search)==0 and count($verical_search) == 0 and count($blog_search) == 0 and count($publication_search) == 0 ){
                $result = 'null';
            }
        }
        $html = view('panel.layout.components.search_results', compact('template_search', 'workbook_search', 'ai_chat_search', 'verical_search', 'blog_search', 'publication_search','defix_search', 'result'))->render();
        return response()->json(compact('html'));
    }
}
