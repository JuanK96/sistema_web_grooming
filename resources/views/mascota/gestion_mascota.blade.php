<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
            {{ __('Gestion de Mascotas') }}
        </h2>
    </x-slot>
    <div class="flex flex-row py-10 px-20 justify-between items-center">
        <div>
            <form action="{{ route('mascota.search') }}" method="GET">
                @csrf
                <label class="text-black font-bold" for="buscar">BUSCADOR:</label>
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
            style="background-color:#00405C" href="{{ route('registromascota') }}">
            Registrar Mascota
        </a>
    </div>
    {{-- tabla --}}
    <div class="px-10">
        <table class="min-w-full bg-white rounded border shadow-lg overflow-hidden">
            <thead class="text-white" style="background-color: #00aeef">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Documento Dueño</th>
                    <th class="py-2 px-4">Nombre Mascota</th>
                    <th class="py-2 px-4">Edad</th>
                    <th class="py-2 px-4">Especie</th>
                    <th class="py-2 px-4">Sexo</th>
                    <th class="py-2 px-4">Raza</th>
                    <th class="py-2 px-4">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mascotas as $mascota)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-4 px-4 text-center">{{ $mascota->id }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->dueño->documento }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->name }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->edad }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->especie }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->sexo }}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->raza }}</td>
                        <td class="py-4 px-4 text-center">
                            <button
                                onclick="openVerModal(
                                        {{ $mascota->id }},
                                        '{{ $mascota->dueño->documento }}',
                                        '{{ $mascota->name }}',
                                        '{{ $mascota->edad ?? '' }}',
                                        '{{ $mascota->especie ?? '' }}',
                                        '{{ $mascota->raza ?? '' }}',
                                        '{{ $mascota->sexo ?? '' }}',
                                        '{{ $mascota->peso ?? '' }}',
                                        '{{ $mascota->descripcion ?? '' }}',
                                        '{{ $mascota->enfermedades ?? '' }}',
                                        '{{ $mascota->vacunado ?? '' }}',
                                        '{{ $mascota->esterilizado ?? '' }}',
                                        '{{ $mascota->foto_url ?? '' }}'
                                    )"
                                class="bg-green-500 text-white p-2 h-8 w-8 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z">
                                    </path>
                                </svg>
                            </button>
                            <button
                                onclick="openEditModal(
                                        {{ $mascota->id }},
                                        '{{ $mascota->fecha_nacimiento }}',
                                        '{{ $mascota->peso }}',
                                        '{{ $mascota->esterilizado }}',
                                        '{{ $mascota->vacunado }}',
                                        '{{ $mascota->enfermedades }}',
                                        '{{ $mascota->descripcion }}'
                                    )"
                                class="bg-yellow-500 text-white p-2 h-8 w-8 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="110%"
                                    fill="rgba(255,255,255,1)">
                                    <path
                                        d="M21 15.2426V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5511 3 20.9925V9H9C9.55228 9 10 8.55228 10 8V2H20.0017C20.5531 2 21 2.45531 21 2.9918V6.75736L12.0012 15.7562L11.995 19.995L16.2414 20.0012L21 15.2426ZM21.7782 8.80761L23.1924 10.2218L15.4142 18L13.9979 17.9979L14 16.5858L21.7782 8.80761ZM3 7L8 2.00318V7H3Z">
                                    </path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $mascotas->links() }}
        </div>
    </div>
    {{-- modal editar --}}
    <div id="editModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white rounded-2xl shadow-lg w-2/3 md:w-1/2 p-6">
            <h2 class="text-xl font-semibold mb-4">Editar Mascota</h2>
            <form method="POST" action="{{ route('mascota.update') }}" class=" px-4 w-full space-y-5" 
                id="editForm" enctype="multipart/form-data">
                @csrf
                <input class="hidden" name="idmascota" id="idmascota" type="text">
                <div class="grid grid-cols-4 space-x-4">
                    <!-- edad -->
                    <div>
                        <label for="fechaNacimiento" class="block text-x1 font-medium text-gray-700 mb-1">Fecha de
                            nacimiento</label>
                        <input type="date" id="date" name="date" step="0.1" min="0"
                            required class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200">
                    </div>
                    <!-- Peso -->
                    <div>
                        <label for="weight" class="block text-x1 font-medium text-gray-700 mb-1">Peso (kg)</label>
                        <input type="number" id="weight" name="weight"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                            placeholder="Ej: 5.2">
                    </div>
                    <!-- Esterilizado -->
                    <div>
                        <label class="block text-x1 font-medium text-gray-700 mb-1">Esterilizado</label>
                        <select id="sterilized" name="sterilized"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <!-- Vacunado -->
                    <div>
                        <label class="block text-x1 font-medium text-gray-700 mb-1">Vacunado</label>
                        <select id="vaccinated" name="vaccinated"
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                </div>
                <!-- Enfermedades o condiciones especiales -->
                <div>
                    <label for="diseases" class="block text-x1 font-medium text-gray-700 mb-1">Enfermedades o
                        condiciones especiales</label>
                    <textarea id="diseases" name="diseases" rows="3" maxlength="255"
                        class="w-full px-6 py-6 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Menciona cualquier enfermedad o condición médica que tenga la mascota"></textarea>
                </div>
                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-x1 font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="3" maxlength="255"
                        class="w-full px-6 py-6 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Describe características de la mascota"></textarea>
                </div>
                <!-- Foto -->
                <div class="grid grid-cols-2">
                    <div class="">
                        <label for="photo" class="block text-x1 font-medium text-gray-700 mb-1">Foto de la
                            mascota</label>
                        <div class="mt-1 flex space-x-4 items-center ">
                            <div class="relative">
                                <label for="photo" class="cursor-pointer">
                                    <div
                                        class="bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg w-24 h-24 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition bg-stone-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                </label>
                                <input type="file" id="photo" name="photo" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0">
                            </div>
                        </div>
                        <p class="mt-1 text-x1 text-black">Sube una foto clara de la mascota (JPEG, PNG)</p>
                    </div>
                    <!-- Botón de envío -->
                    <div class="flex items-center justify-end gap-4 pt-1">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
                            style="background-color:#00405C">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Ver Mascota -->
    <div id="verModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white rounded-2xl shadow-lg w-2/3 md:w-1/2 p-6">
            <h2 class="text-xl font-semibold mb-4">Datos de la Mascota</h2>
            <div x-data="{ mascota: {} }" @open-modal.window="mascota = $event.detail">
                <!-- Contenido -->
                <div class="flex gap-6 mt-6">
                    <div x-show="mascota.foto_url" class="flex flex-col items-center w-1/2" id="fotoContainer">
                        <strong class="mb-2">Foto:</strong>
                        <img x-bind:src="mascota.foto_url || 'https://placehold.co/200x200/ffffff/cccccc?text=Sin+foto'"
                            x-bind:alt="'Foto de ' + mascota.name"
                            class="rounded-lg border border-gray-200 w-full max-w-xs h-60 object-cover"
                            onerror="this.src='https://placehold.co/200x200/ffffff/cccccc?text=Error+cargando'; 
                                    this.alt='Error al cargar foto';">
                    </div>
                    <!-- Si no hay foto -->
                    <div x-show="!mascota.foto_url" class="w-1/2 flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <img src="https://placehold.co/200x200/ffffff/cccccc?text=Sin+foto"
                                alt="Sin foto disponible"
                                class="rounded-lg border border-gray-200 w-32 h-32 object-cover mx-auto mb-2">
                            <p>Sin foto disponible</p>
                        </div>
                    </div>
                    <div class="w-1/2 space-y-2 text-gray-800">
                        <p><span class="font-bold">Nombre</span><br><span x-text="mascota.name"></span></p>
                        <p><span class="font-bold">Dueño</span><br><span x-text="mascota.dueno"></span></p>
                        <p><span class="font-bold">Edad</span><br><span x-text="mascota.edad"></span></p>
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <p><span class="font-bold">Especie</span><br><span x-text="mascota.especie"></span>
                            </p>
                            <p><span class="font-bold">Raza</span><br><span x-text="mascota.raza"></span></p>
                            <p><span class="font-bold">Sexo</span><br><span x-text="mascota.sexo"></span></p>
                            <p><span class="font-bold">Peso(kg)</span><br><span x-text="mascota.peso"></span></p>
                        </div>
                    </div>
                </div>
                <hr class="my-4 border-t-2 border-gray-300">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-bold">Descripcion</p>
                        <p x-text="mascota.descripcion"></p>
                    </div>
                    <div>
                        <p class="font-bold">Enfermedades</p>
                        <p x-text="mascota.enfermedades"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <p><span class="font-bold">Vacunado</span><br><span x-text="mascota.vacunado"></span></p>
                    <p><span class="font-bold">Esterilizado</span><br><span x-text="mascota.esterilizado"></span>
                    </p>
                </div>
                <div class="mt-6 flex justify-center">
                    <button type="button" onclick="closeVerModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- edit modal --}}
    <script>
        function openEditModal(id, fecha_nacimiento, peso, esterilizado, vacunado, enfermedades, descripcion, ) {
            // Rellenar campos
            document.getElementById('idmascota').value = id;
            document.getElementById('date').value = fecha_nacimiento;
            document.getElementById('weight').value = peso;
            document.getElementById('sterilized').value = esterilizado;
            document.getElementById('vaccinated').value = vacunado;
            document.getElementById('diseases').value = enfermedades;
            document.getElementById('description').value = descripcion;
            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
    {{-- modal ver --}}
    <script>
        function openVerModal(id, dueno, name, edad, especie, raza, sexo, peso, descripcion, enfermedades,
            vacunado, esterilizado, foto_url) {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: {
                    id,
                    dueno,
                    name,
                    edad,
                    especie,
                    raza,
                    sexo,
                    peso,
                    descripcion,
                    enfermedades,
                    vacunado,
                    esterilizado,
                    foto_url
                }
            }));
            document.getElementById('verModal').classList.remove('hidden');
        }
        function closeVerModal() {
            document.getElementById('verModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
