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
                <input type="text"  name="search" id="search" placeholder="Buscar" pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]+"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();"
                    class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2">
                <button type="submit" class="w-20 h-10 bg-indigo-400 text-white rounded-lg shadow-lg">Buscar</button>
            </form>
        </div>
        <a class="w-50 h-15 text-white content-center text-center font-bold rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
            style="background-color:#00405C" href="{{ route('registromascota') }}">
            Registrar Mascota
        </a>
    </div>
    {{-- tabla --}}
    <div class="px-10">
        <table class="min-w-full bg-white rounded border shadow-lg overflow-hidden">
            <thead class="text-white" style="background-color: #009600">
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
                        <td class="py-4 px-4 text-center">{{ $mascota->dueño->documento }}</< /td>
                        <td class="py-4 px-4 text-center">{{ $mascota->name }}</< /td>
                        <td class="py-4 px-4 text-center">{{ $mascota->edad }}</< /td>
                        <td class="py-4 px-4 text-center">{{ $mascota->especie }}</< /td>
                        <td class="py-4 px-4 text-center">{{ $mascota->sexo }}</< /td>
                        <td class="py-4 px-4 text-center">{{ $mascota->raza }}</< /td>
                        <td class="py-4 px-4 text-center">
                            <button class="bg-green-500 text-white p-2 h-8 w-8 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z">
                                    </path>
                                </svg>
                            </button>
                            <button
                                onclick="openEditModal(
                                        {{ $mascota->id }},
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
    </div>
    {{-- modal --}}
    <div id="editModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white rounded-2xl shadow-lg w-2/3 md:w-1/2 p-6">
            <h2 class="text-xl font-semibold mb-4">Editar Mascota</h2>
            <form method="POST" action="{{ route('mascota.store') }}" class=" px-4 w-full space-y-5" id="petForm">
                @csrf
                <input class="hidden" name="idmascota" id="idmascota" type="text">
                <div class="grid grid-cols-3 space-x-4">
                    <!-- Peso -->
                    <div>
                        <label for="weight" class="block text-x1 font-medium text-gray-700 mb-1">Peso (kg) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="weight" name="weight"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                            placeholder="Ej: 5.2">
                    </div>
                    <!-- Esterilizado -->
                    <div>
                        <label class="block text-x1 font-medium text-gray-700 mb-1">Esterilizado</label>
                        <div class="flex items-center tick-animation ">
                            <input type="checkbox" id="sterilized" name="sterilized" value="1"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300">
                            <label for="sterilized" class="ml-2 text-gray-700">Sí, está esterilizado</label>
                        </div>
                    </div>
                    <!-- Vacunado -->
                    <div>
                        <label class="block text-x1 font-medium text-gray-700 mb-1">Vacunado</label>
                        <div class="flex items-center tick-animation ">
                            <input type="checkbox" id="vaccinated" name="vaccinated" value="1"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 rounded border-gray-300">
                            <label for="vaccinated" class="ml-2 text-gray-700">Sí, está al día con sus
                                vacunas</label>
                        </div>
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
                    <label for="description" class="block text-x1 font-medium text-gray-700 mb-1">Descripción <span
                            class="text-red-500">*</span></label>
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
                            <div id="photoPreview" class="flex-1">
                                <img id="previewImage"
                                    src="https://placehold.co/200x200/ffffff/cccccc?text=Subir+foto"
                                    alt="Previsualización de imagen de mascota donde aparecerá la foto subida por el usuario"
                                    class="w-24 h-24 rounded-lg object-cover border border-gray-200">
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
    <script>
        function openEditModal(id, peso, estarilizado, vacunado, enfermedades, descripcion, ) {
            // Rellenar campos
            document.getElementById('idmascota').value = id;
            document.getElementById('weight').value = peso;
            // document.getElementById('sterilized').value = esterilizado;
            // document.getElementById('vaccinated').value = vacunado;
            document.getElementById('diseases').value = enfermedades;
            document.getElementById('description').value = descripcion;
            // document.getElementById('photo').value = tel_dos;
            // Mostrar modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
