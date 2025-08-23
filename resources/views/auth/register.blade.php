<x-app-layout>
    <x-validation-errors class="mb-4" />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Registro') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center px-20 py-6">
        <form method="POST" action="{{ route('register') }}" class="w-full">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="block text-x1 font-medium text-gray-700">Nombre</label>
                    <input id="name"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>
                <div class="space-y-2">
                    <label for="role" class="block text-x1 font-medium text-gray-700">Rol</label>
                    <select id="role" name="role"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        required>
                        <option value="">Seleccione...</option>
                        <option value="Cedula de Ciudadania">Cedula de Ciudadania</option>
                        <option value="Cedula de Extranjería">Cedula de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="email" class="block text-x1 font-medium text-gray-700">Correo Electronico</label>
                    <input id="email"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>
                <div class="space-y-2">
                    <label for="password" class="block text-x1 font-medium text-gray-700">Contraseña</label>
                    <input id="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        type="password" name="password" required autocomplete="new-password" />
                </div>
                <div class="space-y-2">
                    <label for="name" class="block text-x1 font-medium text-gray-700">Confirmar Contraseña</label>
                    <x-input id="password_confirmation"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>
