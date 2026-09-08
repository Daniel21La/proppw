<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate dynamic XML Sitemap for Google & search engines
     */
    public function sitemap(): Response
    {
        $baseUrl = url('/');
        $cars = RentalMobil::where('status', '!=', 'maintenance')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // 1. Homepage
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$baseUrl}/</loc>\n";
        $xml .= "    <lastmod>" . now()->toAtomString() . "</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>1.0</priority>\n";
        $xml .= "  </url>\n";

        // 2. Booking / Catalog Form
        $xml .= "  <url>\n";
        $xml .= "    <loc>{$baseUrl}/transaksi/create</loc>\n";
        $xml .= "    <lastmod>" . now()->toAtomString() . "</lastmod>\n";
        $xml .= "    <changefreq>daily</changefreq>\n";
        $xml .= "    <priority>0.9</priority>\n";
        $xml .= "  </url>\n";

        // 3. Dynamic Car Details Pages
        foreach ($cars as $car) {
            $lastmod = ($car->updated_at ?? now())->toAtomString();
            $carUrl = "{$baseUrl}/mobil/{$car->id}";

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$carUrl}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
