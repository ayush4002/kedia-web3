<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Unit;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Disallow: /booking',
            'Disallow: /api',
            'Disallow: /language',
            'Disallow: /cpanel-setup',
            'Disallow: /account',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $urls = [];
        foreach (['home', 'projects.index', 'about', 'gallery', 'tools', 'nri', 'careers', 'contact', 'legal'] as $name) {
            $urls[] = ['loc' => route($name), 'lastmod' => null];
        }

        foreach (Project::orderBy('sort_order')->get(['slug', 'updated_at']) as $project) {
            $urls[] = ['loc' => route('projects.show', $project->slug), 'lastmod' => $project->updated_at];
        }

        foreach (Unit::orderBy('project_slug')->orderBy('unit_code')->get(['project_slug', 'unit_code', 'updated_at']) as $unit) {
            $urls[] = ['loc' => route('units.show', [$unit->project_slug, $unit->unit_code]), 'lastmod' => $unit->updated_at];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>' . e($url['loc']) . '</loc>'
                . ($url['lastmod'] ? '<lastmod>' . $url['lastmod']->toAtomString() . '</lastmod>' : '')
                . "</url>\n";
        }
        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
