<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Confirmation</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Ceci est une zone sécurisée. Veuillez confirmer votre mot de passe avant de continuer.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Confirmer
        </x-primary-button>
    </form>
</x-guest-layout>
