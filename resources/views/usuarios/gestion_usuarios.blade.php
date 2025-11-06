<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion de Usuarios') }}
        </h2>
    </x-slot>
    <div class="flex flex-row py-10 px-20 justify-between items-center">
        <div>
            <form action="{{ route('user.search') }}" method="GET">
                @csrf
                <label class="text-black font-bold" for="buscar">BUSCADOR:</label>
                <input type="text" name="search" id="search" placeholder="Buscar" {{-- oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();" --}}
                    class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2">
                <button type="submit" class="w-20 h-10 bg-indigo-400 text-white rounded-lg shadow-lg">Buscar</button>
            </form>
        </div>
        <div>
            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="flex flex-row p-1 gap-1 bg-red-100 border border-red-400 text-red-700 rounded relative"
                    role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Mensajes flash de error --}}
            @if (session('error'))
                <div class="flex flex-row p-1 gap-1 bg-red-100 border border-red-400 text-red-700 rounded relative"
                    role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            {{-- Mensajes flash de éxito --}}
            @if (session('success'))
                <div class="flex flex-row p-1 gap-1 bg-green-100 border border-green-400 text-green-700 rounded relative"
                    role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
        </div>
        <button id="openModal" type="submit"
            class="w-50 h-15 text-white content-center text-center font-bold rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
            style="background-color:#00405C">
            Registrar Usuario
        </button>
    </div>
    {{-- modal registro --}}
    <div id="modal"
        class="fixed inset-0  backdrop-blur-sm backdrop-opacity-100
            bg-opacity-0 hidden justify-center items-center">
        <div class="flex justify-center items-center h-full w-full">
            <div class="bg-white rounded-lg p-8 w-1/2 fade-in modal-contet z-50 border border-gray-300">
                <form method="POST" action="{{ route('user.create') }}" class="w-full"
                    onsubmit="return validatePasswords()">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-x1 font-medium text-gray-700">Nombre</label>
                            <input id="name"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                                type="text" name="name" :value="old('name')" required autofocus
                                autocomplete="name" pattern="[A-ZÁÉÍÓÚÑÜ\s]+"
                                oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s]/g, '').toUpperCase();" />
                        </div>
                        <div class="space-y-2">
                            <label for="role" class="block text-x1 font-medium text-gray-700">Rol</label>
                            <select id="role" name="role"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                                required>
                                <option value="">Seleccione...</option>
                                <option value="1">Admin</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Groomer</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="email" class="block text-x1 font-medium text-gray-700">Correo
                                Electronico</label>
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
                            <label for="name" class="block text-x1 font-medium text-gray-700">Confirmar
                                Contraseña</label>
                            <input id="password_confirmation"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                                type="password" name="password_confirmation" required autocomplete="new-password" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4 gap-2">
                        <button type="reset" id="closeModal"
                            class="px-4 py-2 bg-red-200 hover:bg-red-300 text-gray-800 rounded-lg transition duration-200">
                            Cancelar
                        </button>
                        <button
                            class="px-4 py-2 bg-indigo-200 hover:bg-indigo-300 text-gray-800 rounded-lg transition duration-200">
                            Registrar
                        </button>
                    </div>
                </form>
                <script>
                    function validatePasswords() {
                        const password = document.getElementById('password').value;
                        const passwordConfirmation = document.getElementById('password_confirmation').value;
                        if (password !== passwordConfirmation) {
                            alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
                            return false; // Evita el envío del formulario
                        }
                        return true; // Permite el envío del formulario
                    }
                </script>
            </div>
        </div>
    </div>
    {{-- tabla --}}
    <div class="px-10">
        <table class="min-w-full bg-white rounded border shadow-lg overflow-hidden">
            <thead class="text-white" style="background-color: #00aeef">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Correo</th>
                    <th class="py-2 px-4">Rol</th>
                    <th class="py-2 px-4">Estado</th>
                    <th class="py-2 px-4">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-4 px-4 text-center">{{ $usuario->id }}</td>
                        <td class="py-4 px-4 text-center">{{ $usuario->name }}</td>
                        <td class="py-4 px-4 text-center">{{ $usuario->email }}</td>
                        <td class="py-4 px-4 text-center">{{ $usuario->role->name }}</td>
                        <td class="py-4 px-4 text-center">
                            @if ($usuario->active == 1)
                                Activo
                            @else
                                Desactivado
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('usuarios.toggle', $usuario->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="p-2 h-10 w-auto rounded-lg text-white
                                    {{ $usuario->active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }}">
                                    {{ $usuario->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('openModal');
            const closeModalBtn = document.getElementById('closeModal');
            const modal = document.getElementById('modal');

            openModalBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Opcional: cerrar al hacer clic fuera del contenido del modal
            window.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
