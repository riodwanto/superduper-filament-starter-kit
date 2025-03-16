<?php

namespace App\Filament\Resources\ContactUsResource\Actions;

use App\Models\ContactUs;
use Filament\Forms;
// use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
// use Illuminate\Support\Facades\Mail;

class ReplyAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->icon('heroicon-o-paper-airplane');

        $this->color('success');

        $this->modalHeading(fn (ContactUs $record): string => "Reply to {$record->name}");

        $this->modalIcon(FilamentIcon::resolve('heroicon-o-paper-airplane'));

        $this->form([
            Forms\Components\TextInput::make('reply_title')
                ->label('Subject')
                ->required()
                ->maxLength(255),
            Forms\Components\RichEditor::make('reply_message')
                ->label('Message')
                ->required()
                ->columnSpanFull(),
        ]);

        $this->action(function (array $data, ContactUs $record): void {
            $record->update([
                'reply_title' => $data['reply_title'],
                'reply_message' => $data['reply_message'],
                'status' => 'read',
            ]);

            // TODO sending logic
            // something like this:
            // Mail::to($record->email)
            //     ->send(new \App\Mail\ContactReply($record));

            Notification::make()
                ->title('Reply sent successfully')
                ->success()
                ->send();
        });
    }

    public static function getDefaultName(): ?string
    {
        return 'reply';
    }
}
