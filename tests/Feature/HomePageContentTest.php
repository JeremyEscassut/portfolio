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
        $response->assertSee('<dialog id="video-dialog"', false);
        $response->assertSee('video/mp4', false);
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
        $response->assertSee('<dialog id="video-dialog"', false);
        $response->assertSee('video/mp4', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function carousel_slides_and_dots_are_server_rendered_with_their_labels()
    {
        // The screenshots used to exist only once Alpine ran (x-for + :src). They are now
        // plain HTML, with a position label per slide for screen readers.
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('aria-roledescription="slide" aria-label="Capture 1 sur 3"', false);
        $response->assertSee('aria-label="Capture 3 sur 3"', false);
        $response->assertSee('images/projects/admiral-tdb-client.webp', false);
        $response->assertSee('aria-label="Captures d&#039;écran 2"', false);
        $response->assertSee('role="status" aria-live="polite" data-carousel-status', false);
        $response->assertDontSee(':aria-label=', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function english_carousel_slide_labels_are_translated()
    {
        $response = $this->get(route('home', ['locale' => 'en']));

        $response->assertOk();
        $response->assertSee('aria-label="Screenshot 2 of 3"', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function modals_are_native_dialogs_without_manual_aria_modal()
    {
        // showModal() already makes the page inert, keeps focus inside and handles Escape.
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('<dialog id="contact-dialog" class="pf-dialog" aria-labelledby="contact-title"', false);
        $response->assertSee('<dialog id="cv-selector-modal" class="pf-dialog" aria-labelledby="cv-modal-title"', false);
        $response->assertSee('<dialog id="video-dialog" class="pf-dialog" aria-labelledby="video-dialog-title"', false);
        $response->assertDontSee('aria-modal', false);
        $response->assertDontSee('role="dialog"', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function theme_is_applied_by_an_inline_script_before_the_styles()
    {
        // No white flash for dark-mode visitors: the theme is set before the CSS renders,
        // and follows the system setting when nothing is stored.
        $html = $this->get(route('home', ['locale' => 'fr']))->getContent();

        $script = strpos($html, "matchMedia('(prefers-color-scheme: dark)')");
        $styles = strpos($html, 'rel="stylesheet"');
        $this->assertNotFalse($script);
        $this->assertNotFalse($styles);
        $this->assertLessThan($styles, $script);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function the_mobile_section_fan_starts_with_a_back_to_top_link_in_both_languages()
    {
        foreach (['fr' => 'Haut de page', 'en' => 'Back to top'] as $locale => $label) {
            $html = $this->get(route('home', ['locale' => $locale]))->getContent();

            $menu = substr($html, strpos($html, 'id="section-fab-menu"'));
            $top = strpos($menu, 'href="#top"');
            $about = strpos($menu, 'href="#about"');

            $this->assertNotFalse($top, "[$locale] back-to-top link missing from the fan");
            $this->assertLessThan($about, $top, "[$locale] back-to-top must be the first link");
            $this->assertStringContainsString($label, substr($menu, $top, 600));
        }
    }
}
