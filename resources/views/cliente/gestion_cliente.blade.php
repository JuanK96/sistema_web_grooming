<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-white leading-tight">
            {{ __('Gestion de Clientes') }}
        </h2>
    </x-slot>
    <div class="flex flex-row py-10 px-20 justify-between items-center">
        <div>
            <label class="text-black font-bold" for="buscar">BUSCADOR:</label>
            <form action="{{ route('cliente.search') }}" method="GET">
                @csrf
                <input type="text" name="search" id="search" placeholder="Buscar" pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]+"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();"
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
        <a class="w-50 h-15 text-white content-center text-center font-bold rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
            style="background-color:#00405C" href="{{ route('registrocliente') }}">
            Registrar Cliente
        </a>
    </div>
    {{-- tabla --}}
    <div class="px-10">
        <table class="min-w-full bg-white rounded border shadow-lg overflow-hidden">
            <thead class="text-white" style="background-color: #00aeef">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Documento</th>
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Direccion</th>
                    <th class="py-2 px-4">Correo</th>
                    <th class="py-2 px-4">Telefono</th>
                    <th class="py-2 px-4">Tel Alterno</th>
                    @if (Auth::user()->id_role != 3)
                        <th class="py-2 px-4">ACCIONES</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-4 px-4 text-center">{{ $cliente->id }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->documento }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->name }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->direccion }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->email }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->tel }}</td>
                        <td class="py-4 px-4 text-center">{{ $cliente->tel_dos }}</td>
                        @if (Auth::user()->id_role != 3)
                            <td class="py-4 px-4 text-center">
                                <!-- Botón editar -->
                                <button
                                    onclick="openEditModal(
                                        {{ $cliente->id }},
                                        '{{ $cliente->name }}',
                                        '{{ $cliente->email }}',
                                        '{{ $cliente->direccion }}',
                                        '{{ $cliente->tel }}',
                                        '{{ $cliente->tel_dos }}'
                                    )"
                                    class="bg-yellow-500 text-white p-2 h-8 w-8 rounded-lg  focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="110%"
                                        fill="rgba(255,255,255,1)">
                                        <path
                                            d="M21 15.2426V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5511 3 20.9925V9H9C9.55228 9 10 8.55228 10 8V2H20.0017C20.5531 2 21 2.45531 21 2.9918V6.75736L12.0012 15.7562L11.995 19.995L16.2414 20.0012L21 15.2426ZM21.7782 8.80761L23.1924 10.2218L15.4142 18L13.9979 17.9979L14 16.5858L21.7782 8.80761ZM3 7L8 2.00318V7H3Z">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $clientes->links() }}
        </div>
    </div>
    <!-- Modal -->
    <div id="editModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white rounded-2xl shadow-lg w-2/3 md:w-1/2 p-6">
            <h2 class="text-xl font-semibold mb-4">Editar Cliente</h2>
            <form id="editForm" method="POST" action="{{ route('cliente.update') }}">
                @csrf
                <input class="hidden" name="idcliente" id="idcliente" type="text">
                <!-- Nombre -->
                <div class="space-y-2 mt-4">
                    <label for="edit_name" class="block text-x1 font-medium text-gray-700">
                        Nombre completo
                    </label>
                    <input type="text" name="nombreCompleto" id="edit_name" pattern="[A-ZÁÉÍÓÚÑÜ\s]+"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s]/g, '').toUpperCase();"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-stone-200">
                </div>
                <div class="space-y-2">
                    <label for="edit_email" class="block text-x1 font-medium text-gray-700">
                        Correo electrónico
                    </label>
                    <input type="email" name="correoElectronico" id="edit_email"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                </div>
                <!-- Dirección -->
                <div class="space-y-2 mt-4">
                    <label for="edit_direccion" class="block text-x1 font-medium text-gray-700">
                        Dirección
                    </label>
                    <input type="text" name="direccion" id="edit_direccion" pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]+"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-stone-200">
                </div>
                <!-- Teléfonos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div class="space-y-2">
                        <label for="edit_tel" class="block text-x1 font-medium text-gray-700">
                            Teléfono
                        </label>
                        <input type="tel" name="telefono" id="edit_tel" maxlength="15" maxlength="15"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                    </div>
                    <div class="space-y-2">
                        <label for="edit_tel_dos" class="block text-x1 font-medium text-gray-700">
                            Teléfono alterno
                        </label>
                        <input type="tel" name="telefonoAlterno" id="edit_tel_dos" maxlength="15" maxlength="15"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                    </div>
                </div>
                <!-- Botones -->
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white rounded-md  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        style="background-color:#00405C">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openEditModal(id, name, email, direccion, tel, tel_dos, ) {
            // Rellenar campos
            document.getElementById('idcliente').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_direccion').value = direccion;
            document.getElementById('edit_tel').value = tel;
            document.getElementById('edit_tel_dos').value = tel_dos;
            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
