<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function pages_send_the_security_headers()
    {
        $response = $this->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Content-Security-Policy', "frame-ancestors 'self'");
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // HTTP request in tests: HSTS is only sent over HTTPS
        $response->assertHeaderMissing('Strict-Transport-Security');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function hsts_is_sent_over_https()
    {
        $this->get('https://localhost/fr')
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function the_contact_form_accepts_three_messages_per_ten_minutes_then_offers_the_fallback()
    {
        Mail::fake();
        $home = route('home', ['locale' => 'fr']);
        $post = fn () => $this->from($home)->post(route('contact.send', ['locale' => 'fr']), [
            'email' => 'jean@example.test',
            'message' => 'Bonjour',
            'hp' => '',
        ]);

        foreach (range(1, 3) as $i) {
            $post()->assertSessionHas('status');
        }

        $response = $post();
        $response->assertRedirect($home);
        $response->assertSessionHas('contactThrottled', true);
        $response->assertSessionHas('contactFailed', true);
        $response->assertSessionHasInput('message', 'Bonjour');
        Mail::assertSent(ContactMessage::class, 3);

        $page = $this->withSession(['contactFailed' => true, 'contactThrottled' => true])->get($home);
        $page->assertSee('Merci de réessayer dans quelques minutes.', false);
    }
}
