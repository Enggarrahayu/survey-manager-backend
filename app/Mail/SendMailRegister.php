<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $team_name, $team_owner)
    {
        $this->email = $email;
        $this->team_name = $team_name;
        $this->team_owner = $team_owner;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('surveymanager@gmail.com')
            ->view('emailRegistration')
            ->with(
            [
                'email' => $this->email,
                'team_name' => $this->team_name,
                'team_owner' => $this->team_owner,
            ]);
    }
}
