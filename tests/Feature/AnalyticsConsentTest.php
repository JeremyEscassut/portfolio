<?php

namespace Tests\Feature;

use Tests\TestCase;

class AnalyticsConsentTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function google_analytics_is_not_loaded_before_consent_and_the_banner_is_rendered()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        // The gtag script must not be in the server-rendered HTML: app.js injects it after "Accepter".
        $response->assertDontSee('googletagmanager.com/gtag/js', false);
        $response->assertSee('id="consent-banner"', false);
        $response->assertSee('data-consent="denied"', false);
        $response->assertSee('data-consent="granted"', false);
        $response->assertSee('data-consent-open', false);
    }
}
