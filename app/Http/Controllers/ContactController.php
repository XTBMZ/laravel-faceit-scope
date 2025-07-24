<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Affiche la page de contact
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Traite l'envoi du formulaire de contact
     */
    public function submit(Request $request): JsonResponse
    {
        
        $key = 'contact-form:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Trop de messages envoyés. Veuillez patienter avant de réessayer.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        
        $validated = $request->validate([
            'email' => 'nullable|email|max:255',
            'pseudo' => 'nullable|string|max:100',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000|min:10',
            'type' => 'required|in:bug,suggestion,question,feedback,other'
        ], [
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'subject.required' => 'Le sujet est obligatoire.',
            'subject.max' => 'Le sujet ne peut pas dépasser 255 caractères.',
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
            'message.max' => 'Le message ne peut pas dépasser 5000 caractères.',
            'type.required' => 'Veuillez sélectionner un type de message.',
            'type.in' => 'Type de message invalide.'
        ]);

        try {
            
            RateLimiter::hit($key, 300); 

            
            $emailData = [
                'email' => $validated['email'] ?? 'Non fourni',
                'pseudo' => $validated['pseudo'] ?? 'Non fourni',
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'submitted_at' => now(),
                'ticket_id' => 'FS' . time() . rand(100, 999)
            ];

            
            Mail::to(config('mail.contact.recipient', 'support@faceitscope.com'))
                ->send(new \App\Mail\ContactFormMail($emailData));

            
            Log::info('Formulaire de contact soumis', [
                'ticket_id' => $emailData['ticket_id'],
                'type' => $validated['type'],
                'has_email' => !empty($validated['email']),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès !',
                'ticket_id' => $emailData['ticket_id']
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi formulaire contact', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer plus tard.'
            ], 500);
        }
    }

    /**
     * Retourne le label d'un type de message
     */
    private function getTypeLabel(string $type): string
    {
        $types = [
            'bug' => 'Bug Report',
            'suggestion' => 'Suggestion',
            'question' => 'Question',
            'feedback' => 'Feedback',
            'other' => 'Autre'
        ];

        return $types[$type] ?? 'Contact';
    }
}