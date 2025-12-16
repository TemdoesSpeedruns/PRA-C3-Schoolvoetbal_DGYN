<?php

namespace App\Mail;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SchoolRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public School $school;
    public ?string $result; // null = initial confirmation, 'approved' or 'rejected'

    public function __construct(School $school, ?string $result = null)
    {
        $this->school = $school;
        $this->result = $result;
    }

    public function build()
    {
        if ($this->result === 'approved') {
            $subject = 'Uw schoolaccount is goedgekeurd';
        } elseif ($this->result === 'rejected') {
            $subject = 'Uw schoolaccount is afgewezen';
        } else {
            $subject = 'Bevestiging van registratie';
        }

        return $this->subject($subject)
                    ->view('emails.registration_confirmation')
                    ->with([
                        'school' => $this->school,
                        'result' => $this->result,
                    ]);
    }
}