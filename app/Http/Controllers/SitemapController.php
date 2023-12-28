<?php

namespace App\Http\Controllers;

use App\Models\Sitemap;
use App\Models\SitemapPages;

use Illuminate\Http\Request;


class SitemapController extends Controller
{
    public static function generateSitemap()
    {
        self::indexSitemap();
    }

    protected static function indexSitemap()
    {
        $urls = Sitemap::orderBy('group_by', 'ASC')->get();
        $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>
        <sitemapindex  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($urls as $url) {

            // Calling innerSitemap function to load internal links if they have any
            self::innerSitemap( $url->id, $url->file_name );

            $sitemapContent .= '
            <sitemap>
                <loc>' . route('index').'/'. $url->file_name. '_sitemap.xml' . '</loc>
                <lastmod>' . now()->toAtomString() . '</lastmod>
            </sitemap>';
        }
        
        $sitemapContent .= '
        </sitemapindex >';

        file_put_contents( public_path('sitemap_index.xml'), $sitemapContent );
    }


    protected static function innerSitemap( $sitemapId, $fileName )
    {
        $urls = SitemapPages::where('sitemap_id', $sitemapId )->get();
        $fileName = $fileName . "_sitemap.xml";
        
        $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
        
        foreach ($urls as $url) {

            $sitemapContent .= '
            <url>
                <loc>' . $url->url . '</loc>
                <lastmod>' . now()->toAtomString() . '</lastmod>
                <changefreq>' . $url->frequency . '</changefreq>
                <priority>' . $url->priority . '</priority>
            </url>';
        }
        
        $sitemapContent .= '
        </urlset>';

        file_put_contents( public_path( $fileName ), $sitemapContent );
    }

}
