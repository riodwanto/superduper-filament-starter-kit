<?php

namespace App\Filament\Resources\ContactUsResource\Actions;

use App\Models\ContactUs;
use App\Mail\ContactReply;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class ReplyAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-paper-airplane');

        $this->color('success');

        $this->modalHeading(fn (ContactUs $record): string => "Reply to {$record->name}");

        $this->modalDescription(fn (ContactUs $record): string => "Responding to {$record->subject}");

        $this->modalIcon(FilamentIcon::resolve('heroicon-o-paper-airplane'));

        $this->form([
            Forms\Components\TextInput::make('reply_subject')
                ->label('Subject')
                ->required()
                ->maxLength(255)
                ->default(fn (ContactUs $record): string => "RE: {$record->subject}"),
            Forms\Components\RichEditor::make('reply_message')
                ->label('Message')
                ->required()
                ->fileAttachmentsDisk('public')
                ->fileAttachmentsDirectory('contact-replies')
                ->fileAttachmentsVisibility('public')
                ->columnSpanFull(),
        ]);

        $this->action(function (array $data, ContactUs $record): void {
            try {
                // Use the addReply helper method to update the record
                $record->addReply(
                    $data['reply_subject'],
                    $data['reply_message'],
                    auth()->user()
                );

                // Get mail settings
                $mailSettings = app(MailSettings::class);

                // Check if mail settings are configured
                if (!$mailSettings->isMailSettingsConfigured()) {
                    Log::warning('Mail settings not configured. Reply email not sent for contact ID: ' . $record->id);

                    Notification::make()
                        ->title('Reply saved but email not sent')
                        ->body('Mail settings are not properly configured. The reply has been saved but the email could not be sent.')
                        ->warning()
                        ->send();

                    return;
                }

                // Load mail settings
                $mailSettings->loadMailSettingsToConfig();

                // Send the reply email
                try {
                    Mail::to($record->email)
                        ->queue(new ContactReply($record));

                    Log::info('Contact reply email queued successfully', [
                        'contact_id' => $record->id,
                        'recipient' => $record->email
                    ]);

                    Notification::make()
                        ->title('Reply sent successfully')
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                    Log::error('Failed to send contact reply email', [
                        'contact_id' => $record->id,
                        'recipient' => $record->email,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    Notification::make()
                        ->title('Reply saved but email failed to send')
                        ->body('The reply has been saved but there was an error sending the email: ' . $e->getMessage())
                        ->warning()
                        ->send();
                }

            } catch (\Exception $e) {
                Log::error('Error processing contact reply', [
                    'contact_id' => $record->id ?? 'unknown',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                Notification::make()
                    ->title('Error processing reply')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'reply';
    }
}
