<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
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
            return redirect()->back()->with('status', "Votre message a bien été envoyé. Merci !");
        }

        $nameForMessage = $data['name'] ?? null;

        $to = config('mail.contact_address') ?? env('MAIL_CONTACT_ADDRESS');

        try {
            // avoid passing a key named "message" (conflicts with Mail\Message in Blade)
            Mail::to($to)->send(new ContactMessage([
                'name' => $nameForMessage,
                'email' => $data['email'],
                'body' => $data['message']
            ]));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue lors de l\'envoi du message.')->with('openContact', true);
        }
        $displayName = $nameForMessage ? ' ' . trim($nameForMessage) : '';
        $personal = $displayName ? " {$displayName}" : '';
        // Friendly, clear success message for the user
        return redirect()->back()->with('status', "Votre message a bien été envoyé. Merci{$personal} — je vous répondrai dès que possible.");
    }
}
