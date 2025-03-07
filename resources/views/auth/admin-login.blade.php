<x-guest-layout>
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <p class="text-white text-center">Admin</p>

        <!-- Email Field -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
            
            <!-- Show Email Error -->
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />

            <!-- Show Password Error -->
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Show General Errors (e.g., Authentication Error) -->
       

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>{{ __('Log in') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
