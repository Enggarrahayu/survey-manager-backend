<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $team_name, $accept_id, $team_owner)
    {
        $this->username = $username;
        $this->accept_id = $accept_id;
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
            ->view('email')
            ->with(
            [
                'username' => $this->username,
                'team_name' => $this->team_name,
                'accept_id' => $this->accept_id,
                'team_owner' => $this->team_owner,
            ]);
    }
} 