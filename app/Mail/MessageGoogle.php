<?php

namespace App\Mail;

use App\Models\Personne;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MessageGoogle extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->data = Str::random(10);

        $personne = Personne::where('email', request('email'))->first();
        $user = User::where('id', $personne->user_id)->first();

        $update_user = $user->update([
            'password' => Hash::make($this->data)
        ]);
    }

    public function build()
    {
        return $this->markdown('emails.message_google')
            ->subject('Récupération du mot de passe')
            ->from('philippesf3@gmail.com', 'MAKE-IT');
    }
}
