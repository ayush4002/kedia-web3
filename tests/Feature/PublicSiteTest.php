<?php

namespace Tests\Feature;

use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_pages_render(): void
    {
        $unit = Unit::first();
        foreach (['/', '/projects', '/projects/kedias-landmark', "/projects/{$unit->project_slug}/units/{$unit->unit_code}",
                  '/about', '/about/chairman', '/about/vision-mission', '/why-invest', '/gallery', '/tools', '/nri', '/careers', '/contact',
                  '/legal', '/privacy-policy', '/terms', '/refund-booking-policy', '/account', '/compare', '/projects?view=units',
                  '/projects?view=units&type=villa&bedrooms=3&sort=price-low', '/projects?layout=list&status=completed', "/units/{$unit->id}/cost-sheet"] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_unknown_project_and_unit_return_404(): void
    {
        $this->get('/projects/no-such-project')->assertNotFound();
        $this->get('/projects/kedias-landmark/units/NOPE')->assertNotFound();
        $this->get('/booking/999999')->assertNotFound();
    }

    public function test_legacy_unit_links_redirect_only_when_unambiguous(): void
    {
        $unique = Unit::selectRaw('unit_code')->groupBy('unit_code')->havingRaw('count(*) = 1')->value('unit_code');
        $unit = Unit::where('unit_code', $unique)->first();
        $this->get("/unit/{$unique}")->assertRedirect(route('units.show', [$unit->project_slug, $unit->unit_code]));

        $shared = Unit::selectRaw('unit_code')->groupBy('unit_code')->havingRaw('count(*) > 1')->value('unit_code');
        $this->assertNotNull($shared, 'seed data should contain unit codes reused across projects');
        $this->get("/unit/{$shared}")->assertRedirect(route('projects.index'));
    }

    public function test_api_requires_project_for_shared_unit_codes(): void
    {
        $shared = Unit::selectRaw('unit_code')->groupBy('unit_code')->havingRaw('count(*) > 1')->value('unit_code');
        $this->getJson("/api/units/{$shared}")->assertStatus(409);

        $slug = Unit::where('unit_code', $shared)->value('project_slug');
        $this->getJson("/api/units/{$shared}?project={$slug}")
            ->assertOk()
            ->assertJsonPath('unit.project_slug', $slug);
    }

    public function test_seo_endpoints_and_meta(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap:');
        $this->get('/sitemap.xml')->assertOk()->assertSee('<urlset', false)->assertSee('/projects/kedias-landmark', false);

        $this->get('/projects/kedias-landmark')
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('"@type":"Residence"', false)
            ->assertSee('content="index, follow"', false);

        $this->get('/account')->assertSee('content="noindex, nofollow"', false);
        $this->get('/dashboard')->assertRedirect('/account');
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_hindi_locale_translates_pages(): void
    {
        $this->withSession(['locale' => 'hi'])->get('/contact')
            ->assertOk()
            ->assertSee('हमारे जयपुर मुख्यालय से जुड़ें');

        // Switching back through the language toggle restores English.
        $this->get('/language/en');
        $this->get('/contact')->assertSee('Connect With Our Jaipur Headquarters');
    }

    public function test_brochure_link_only_when_file_exists(): void
    {
        $this->get('/projects/kedias-landmark')->assertSee('/media/docs/kedias-landmark-brochure.pdf', false);
        $this->get('/projects/kedia-aravali')->assertDontSee('kedia-aravali-brochure.pdf', false);
    }
}
