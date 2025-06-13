<!-- resources/views/profile/two-factor-authentication-form.blade.php -->
<x-card>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Two Factor Authentication') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add additional security to your account using two factor authentication.') }}
        </p>
    </x-slot>

    <div>
        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">
                        {{ __('Scan the following QR code using your phone\'s authenticator application.') }}
                    </p>

                    <div class="mt-4" v-html="{{ $this->qrCodeSvg() }}">
                        {!! $this->qrCodeSvg() !!}
                    </div>
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">
                        {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                    </p>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                        @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                            <div>{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <x-slot name="footer">
        @if (! $this->enabled)
            <x-confirms-password wire:then="enableTwoFactorAuthentication">
                <x-button type="button" wire:loading.attr="disabled">
                    {{ __('Enable') }}
                </x-button>
            </x-confirms-password>
        @else
            @if ($showingRecoveryCodes)
                <x-confirms-password wire:then="regenerateRecoveryCodes">
                    <x-secondary-button class="mr-3">
                        {{ __('Regenerate Recovery Codes') }}
                    </x-secondary-button>
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="showRecoveryCodes">
                    <x-secondary-button class="mr-3">
                        {{ __('Show Recovery Codes') }}
                    </x-secondary-button>
                </x-confirms-password>
            @endif

            <x-confirms-password wire:then="disableTwoFactorAuthentication">
                <x-danger-button wire:loading.attr="disabled">
                    {{ __('Disable') }}
                </x-danger-button>
            </x-confirms-password>
        @endif
    </x-slot>
</x-card>