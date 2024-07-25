<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class Login extends BaseAuth
{
    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $login_type => $data['login'],
            'password'  => $data['password'],
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ])->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Email / Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }
}
