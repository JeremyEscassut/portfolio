<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function each_page_has_a_canonical_url_and_its_language_alternates()
    {
        foreach (['home', 'cv', 'mentions'] as $page) {
            foreach (['fr', 'en'] as $locale) {
                $html = $this->get(route($page, ['locale' => $locale]))->assertOk()->getContent();

                $this->assertStringContainsString('<link rel="canonical" href="' . route($page, ['locale' => $locale]) . '">', $html, "$page/$locale");
                $this->assertStringContainsString('hreflang="fr" href="' . route($page, ['locale' => 'fr']) . '"', $html);
                $this->assertStringContainsString('hreflang="en" href="' . route($page, ['locale' => 'en']) . '"', $html);
                $this->assertStringContainsString('hreflang="x-default" href="' . route($page, ['locale' => 'fr']) . '"', $html);
            }
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function the_root_url_points_to_the_french_home_page_as_canonical()
    {
        $this->get('/')->assertOk()
            ->assertSee('<link rel="canonical" href="' . route('home', ['locale' => 'fr']) . '">', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function link_previews_use_the_page_language()
    {
        $this->get(route('home', ['locale' => 'en']))
            ->assertSee('<meta property="og:image" content="' . asset('images/og-en.jpg') . '">', false)
            ->assertSee('<meta property="og:locale" content="en_US">', false)
            ->assertSee('Product owner, IT project manager and UX/UI developer', false)
            ->assertSee('"@type":"Person"', false);

        $this->get(route('home', ['locale' => 'fr']))
            ->assertSee('<meta property="og:image" content="' . asset('images/og-fr.jpg') . '">', false)
            ->assertSee('<meta property="og:locale" content="fr_FR">', false);

        $this->assertFileExists(public_path('images/og-fr.jpg'));
        $this->assertFileExists(public_path('images/og-en.jpg'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function unknown_addresses_return_a_real_404_page_in_the_right_language()
    {
        $this->get('/fr/n-importe-quoi')->assertNotFound()
            ->assertSee('Page introuvable')
            ->assertSee('<meta name="robots" content="noindex">', false)
            ->assertDontSee('rel="canonical"', false);

        $this->get('/en/whatever')->assertNotFound()->assertSee('Page not found');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function the_sitemap_lists_every_page_in_both_languages_and_robots_txt_declares_it()
    {
        $sitemap = file_get_contents(public_path('sitemap.xml'));
        foreach (['/fr', '/en', '/fr/cv', '/en/cv', '/fr/mentions', '/en/mentions'] as $path) {
            $this->assertStringContainsString("<loc>https://www.jeremyescassut.com$path</loc>", $sitemap);
        }
        $this->assertStringContainsString('Sitemap: https://www.jeremyescassut.com/sitemap.xml', file_get_contents(public_path('robots.txt')));
    }
}
