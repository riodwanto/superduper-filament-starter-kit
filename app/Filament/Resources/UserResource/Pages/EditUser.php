<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Support;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use App\Filament\Pages\Actions\ImpersonatePageAction;
use Illuminate\Support\Facades\Blade;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            ImpersonatePageAction::make()->record($this->record),
            Actions\ActionGroup::make([
                Actions\EditAction::make()
                    ->label(__('resource.user.change_password'))
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->required(),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->same('password')
                            ->required(),
                    ])
                    ->modalWidth(Support\Enums\MaxWidth::Medium)
                    ->modalHeading('Update Password')
                    ->modalDescription(fn($record) => $record->email)
                    ->modalAlignment(Alignment::Center)
                    ->modalCloseButton(false)
                    ->modalSubmitActionLabel('Submit')
                    ->modalCancelActionLabel('Cancel'),

                Actions\DeleteAction::make()
                    ->extraAttributes(["class" => "border-b"]),

                Actions\CreateAction::make()
                    ->label(__('resource.user.create_new_user'))
                    ->url(fn(): string => static::$resource::getNavigationUrl() . '/create'),
            ])
            ->icon('heroicon-m-ellipsis-horizontal')
            ->hiddenLabel()
            ->button()
            ->tooltip('More Actions')
            ->color('gray')
        ];

        return $actions;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        $title = $this->record->name;
        $badge = $this->getBadgeStatus();

        return new HtmlString("
            <div class='flex items-center space-x-2'>
                <div>$title</div>
                $badge
            </div>
        ");
    }

    public function getBadgeStatus(): string|Htmlable
    {
        if (empty($this->record->email_verified_at)) {
            $icon = Blade::render('<x-fluentui-error-circle-24 class="w-5 h-5 text-danger-600" title="Unverified" />');
            $badge = "<span class='inline-flex items-center' title='Unverified'>"
                . $icon . "</span>";
        } else {
            $icon = Blade::render('<x-fluentui-checkmark-starburst-24 class="w-5 h-5 text-success-600" title="Verified" />');
            $badge = "<span class='inline-flex items-center' title='Verified'>"
                . $icon . "</span>";
        }
        return new HtmlString($badge);
    }
}
