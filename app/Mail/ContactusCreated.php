<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class ContactusCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $request;

    /**
     * Create a new message instance.
     */
    public function __construct(Request $request)
    {
        //
        $this->request = $request;
    }
    public function build()
    {
        return $this->view('emails.contact.created')
                    ->subject('New Contact Us Enquiry')
                    ->with(['contact' => $this->request]);
    }
}
