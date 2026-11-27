<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageContentTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function french_home_page_renders_the_demo_cta_and_carousel_navigation_labels()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('Voir la démo', false);
        $response->assertSee('Démonstration Admiral', false);
        $response->assertSee('Voir les captures d&#039;écran précédentes', false);
        $response->assertSee('Voir les captures d&#039;écran suivantes', false);
        $response->assertSee('x-show="videoOpen"', false);
        $response->assertSee('video/quicktime', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function english_home_page_renders_the_english_demo_cta_and_carousel_navigation_labels()
    {
        $response = $this->get(route('home', ['locale' => 'en']));

        $response->assertOk();
        $response->assertSee('See the demo', false);
        $response->assertSee('Admiral demo', false);
        $response->assertSee('View previous screenshots', false);
        $response->assertSee('View next screenshots', false);
        $response->assertSee('x-show="videoOpen"', false);
        $response->assertSee('video/quicktime', false);
    }
}
