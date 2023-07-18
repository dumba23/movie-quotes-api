<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailConfirm extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 */
	public function __construct(public User $user)
	{
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			from: new Address('saba.dumbadze@redberry.com', 'Saba Dumbadze'),
			subject: 'Confirm your mail',
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'mail.confirm',
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
