<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;
use App\Models\User;

class TransactionCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $seller;
    public $buyer;

    /**
     * Create a new message instance.
     */
    public function __construct(Item $item, User $seller, User $buyer)
    {
        $this->item = $item;
        $this->seller = $seller;
        $this->buyer = $buyer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('取引完了のお知らせ - ' . $this->item->name)
                    ->view('emails.transaction-completed');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            'emails.transaction-completed',
            [
                'item' => $this->item,
                'seller' => $this->seller,
                'buyer' => $this->buyer,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}