<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function contact_form_sends_mail_and_sets_session_status_with_name()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'fr']), [
            'name' => 'Jean Test',
            'email' => 'jean@example.test',
            'message' => 'Bonjour, ceci est un test',
            'hp' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Votre message a bien été envoyé. Merci Jean Test — je vous répondrai dès que possible.');

        Mail::assertSent(ContactMessage::class, function ($mail) {
            return $mail->payload['email'] === 'jean@example.test'
                && $mail->payload['body'] === 'Bonjour, ceci est un test';
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contact_form_without_name_still_sends_mail_and_has_generic_status()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'fr']), [
            'name' => null,
            'email' => 'jean2@example.test',
            'message' => 'Bonjour sans nom',
            'hp' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Votre message a bien été envoyé. Merci — je vous répondrai dès que possible.');

        Mail::assertSent(ContactMessage::class, function ($mail) {
            return $mail->payload['email'] === 'jean2@example.test';
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function honeypot_filled_does_not_send_mail_and_returns_success_status()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'fr']), [
            'name' => 'Bot',
            'email' => 'bot@example.test',
            'message' => 'Spam content',
            'hp' => 'filled',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Votre message a bien été envoyé. Merci !');
        Mail::assertNothingSent();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function invalid_email_and_too_long_message_fail_validation_without_sending_mail()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'fr']), [
            'name' => 'Jean',
            'email' => 'not-an-email',
            'message' => str_repeat('a', 5001),
            'hp' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'message']);
        Mail::assertNothingSent();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contact_route_accepts_locale_prefix_for_english_locale()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'en']), [
            'name' => 'Alice',
            'email' => 'alice@example.test',
            'message' => 'Hello from English route',
            'hp' => '',
        ]);

        $response->assertRedirect();
        Mail::assertSent(ContactMessage::class);
    }
}
