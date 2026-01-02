<?php

namespace App\Mail;

use App\Models\AdminInvitation;
use App\Models\AdminPermission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public AdminInvitation $invitation;
    public array $permissionDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(AdminInvitation $invitation)
    {
        $this->invitation = $invitation;

        // Lấy chi tiết các quyền được cấp
        $this->permissionDetails = [];
        foreach ($invitation->permissions ?? [] as $permission) {
            if (isset(AdminPermission::AVAILABLE_PERMISSIONS[$permission])) {
                $this->permissionDetails[$permission] = AdminPermission::AVAILABLE_PERMISSIONS[$permission];
            }
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎮 Lời mời trở thành Admin - Game On Platform',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
