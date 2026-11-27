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

    #[\PHPUnit\Framework\Attributes\Test]
    public function success_message_follows_the_page_language()
    {
        Mail::fake();

        $response = $this->post(route('contact.send', ['locale' => 'en']), [
            'name' => 'Alice',
            'email' => 'alice@example.test',
            'message' => 'Hello',
            'hp' => '',
        ]);

        $response->assertSessionHas('status', 'Your message has been sent. Thank you Alice — I will get back to you as soon as possible.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function failed_send_keeps_the_input_and_asks_the_page_to_offer_the_email_app_fallback()
    {
        config(['app.debug' => false]);
        Mail::shouldReceive('to->send')->andThrow(new \Exception('SMTP down'));

        $response = $this->from(route('home', ['locale' => 'fr']))
            ->post(route('contact.send', ['locale' => 'fr']), [
                'name' => 'Jean',
                'email' => 'jean@example.test',
                'message' => 'Mon message',
                'hp' => '',
            ]);

        $response->assertRedirect(route('home', ['locale' => 'fr']));
        $response->assertSessionHas('error', "Votre message n'a pas pu être envoyé.");
        $response->assertSessionHas('contactFailed', true);
        $response->assertSessionHasInput('message', 'Mon message');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function page_after_a_failed_send_shows_the_mailto_fallback()
    {
        $response = $this->withSession(['contactFailed' => true, 'error' => "Votre message n'a pas pu être envoyé."])
            ->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('data-mailto-fallback', false);
        $response->assertSee('mailto:' . config('mail.public_contact_address'), false);
        $response->assertSee('Ouvrir dans ma messagerie', false);
        // The reopened dialog carries the message; no error toast behind the modal.
        $response->assertSee('data-reopen="message"', false);
        $response->assertDontSee('class="toast toast--error"', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function error_toast_is_still_shown_when_the_dialog_does_not_reopen()
    {
        $response = $this->withSession(['error' => 'Erreur'])
            ->get(route('home', ['locale' => 'fr']));

        $response->assertOk();
        $response->assertSee('class="toast toast--error"', false);
        $response->assertSee('data-reopen=""', false);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function validation_messages_are_in_french_on_the_french_page()
    {
        $response = $this->post(route('contact.send', ['locale' => 'fr']), [
            'email' => 'not-an-email',
            'message' => 'Bonjour',
            'hp' => '',
        ]);

        $response->assertSessionHasErrors(['email' => 'Le champ e-mail doit être une adresse e-mail valide.']);
    }
}
