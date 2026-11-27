<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    /**
     * Handle the contact form (modal in home.blade.php).
     *
     * Flow: validate -> honeypot check -> send ContactMessage by mail -> redirect back
     * with a flash message ('status' on success, 'error' on failure), displayed at the
     * top of the page by home.blade.php.
     *
     * The hidden "hp" field is a honeypot: humans never fill it, bots usually do.
     * When it is filled we pretend everything went fine and send nothing.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'hp' => ['nullable', 'string']
        ]);
        if (!empty($data['hp'])) {
            // Honeypot triggered — pretend success to avoid giving feedback to bots
            return redirect()->back()->with('status', __('contact.sent_short'));
        }

        $nameForMessage = $data['name'] ?? null;

        // Read from config only: env() returns null once `php artisan config:cache` has run (prod).
        $to = config('mail.contact_address');

        try {
            // avoid passing a key named "message" (conflicts with Mail\Message in Blade)
            Mail::to($to)->send(new ContactMessage([
                'name' => $nameForMessage,
                'email' => $data['email'],
                'body' => $data['message']
            ]));
        } catch (\Exception $e) {
            // Full details go to the logs only; the visitor gets a translated message.
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage(), ['exception' => $e]);

            // withInput() keeps what the visitor typed; `contactFailed` makes the page
            // reopen the contact modal with the "send it from your email app" fallback.
            return redirect()->back()
                ->withInput()
                ->with('error', config('app.debug') ? __('contact.failed_debug') : __('contact.failed'))
                ->with('contactFailed', true);
        }

        $displayName = $nameForMessage ? trim($nameForMessage) : '';
        $personal = $displayName !== '' ? " {$displayName}" : '';

        return redirect()->back()->with('status', __('contact.sent', ['name' => $personal]));
    }
}
