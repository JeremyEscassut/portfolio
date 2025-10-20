<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contact_form_sends_mail_and_sets_session_status_with_name()
    {
        Mail::fake();

        $response = $this->post(route('contact.send'), [
            'name' => 'Jean Test',
            'email' => 'jean@example.test',
            'message' => 'Bonjour, ceci est un test',
            'hp' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Message envoyé, merci Jean Test !');

        Mail::assertSent(ContactMessage::class, function ($mail) {
            return $mail->payload['email'] === 'jean@example.test'
                && $mail->payload['message'] === 'Bonjour, ceci est un test';
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contact_form_without_name_still_sends_mail_and_has_generic_status()
    {
        Mail::fake();

        $response = $this->post(route('contact.send'), [
            'name' => null,
            'email' => 'jean2@example.test',
            'message' => 'Bonjour sans nom',
            'hp' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Message envoyé, merci !');

        Mail::assertSent(ContactMessage::class, function ($mail) {
            return $mail->payload['email'] === 'jean2@example.test';
        });
    }
}
