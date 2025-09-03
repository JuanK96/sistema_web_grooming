<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Registro Mascota') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center p-6">
        <form method="POST" action="{{route('mascota.store')}}" class=" px-4 w-full space-y-5" id="petForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-x1 font-medium text-gray-700 mb-1">Nombre<span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" required pattern="[A-ZÁÉÍÓÚÑÜ\s]+"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s]/g, '').toUpperCase();"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Ej: MAX">
                </div>
                <!-- Dueño mascota -->
                <div>
                    <label for="dueño" class="block text-x1 font-medium text-gray-700 mb-1">Dueño Mascota <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="dueño" name="dueño" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="20"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Documento..">
                </div>
                <!-- Especie -->
                <div>
                    <label for="especies" class="block text-x1 font-medium text-gray-700 mb-1">Especie <span
                            class="text-red-500">*</span></label>
                    <select id="especies" name="especies" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200">
                        <option value="" selected disabled>Seleccione una especie...</option>
                        <option value="PERRO">PERRO</option>
                        <option value="GATO">GATO</option>
                    </select>
                </div>
                <!-- Raza -->
                <div>
                    <label for="raza" class="block text-x1 font-medium text-gray-700 mb-1">Raza <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="raza" name="raza" required pattern="[A-ZÁÉÍÓÚÑÜ\s]+"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s]/g, '').toUpperCase();"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Ej: LABRADOR">
                </div>
            </div>
            <!-- Sección de información médica -->
            <div class="space-y-5">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Información Médica</h3>
                <div class="grid grid-cols-5 space-x-4">
                    <!-- Peso -->
                    <div>
                        <label for="weight" class="block text-x1 font-medium text-gray-700 mb-1">Peso (kg) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="weight" name="weight" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                            placeholder="Ej: 5.2">
                    </div>
                    <!-- edad -->
                    <div>
                        <label for="fechaNacimiento" class="block text-x1 font-medium text-gray-700 mb-1">Fecha de
                            nacimiento<span class="text-red-500">*</span></label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" step="0.1" min="0"
                            required class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200">
                    </div>
                    <!-- sexo -->
                    <div>
                        <label class="block text-x1 font-medium text-gray-700 mb-1">Sexo <span
                                class="text-red-500">*</span></label>
                        <div class="flex space-x-4 justify-center">
                            <label class="inline-flex items-center">
                                <input type="radio" name="sex" value="MACHO" required
                                    class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="ml-2 text-gray-700">Macho</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="sex" value="HEMBRA"
                                    class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="ml-2 text-gray-700">Hembra</span>
                            </label>
                        </div>
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
                            <label for="vaccinated" class="ml-2 text-gray-700">Sí, está al día con sus vacunas</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Enfermedades o condiciones especiales -->
            <div class="grid grid-cols-2 gap-2 ">
                <div>
                    <label for="diseases" class="block text-x1 font-medium text-gray-700 mb-1">Enfermedades o
                        condiciones especiales <span
                            class="text-red-500">*</span></label>
                    <textarea id="diseases" name="diseases" rows="3" maxlength="255"
                        class="w-full px-6 py-6 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Menciona cualquier enfermedad o condición médica que tenga la mascota"></textarea>
                </div>
                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-x1 font-medium text-gray-700 mb-1">Descripción <span
                            class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="3" required maxlength="255"
                        class="w-full px-6 py-6 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Describe características de la mascota"></textarea>
                </div>
            </div>
            <!-- Foto -->
            <div class="grid grid-cols-3">
                <div class="">
                    <label for="photo" class="block text-x1 font-medium text-gray-700 mb-1">Foto de la mascota
                        <span class="text-red-500">*</span></label>
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
                            <img id="previewImage" src="https://placehold.co/200x200/ffffff/cccccc?text=Subir+foto"
                                alt="Previsualización de imagen de mascota donde aparecerá la foto subida por el usuario"
                                class="w-24 h-24 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                    <p class="mt-1 text-x1 text-black">Sube una foto clara de la mascota (JPEG, PNG)</p>
                </div>
                <div>
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
                </div>
                <!-- Botón de envío -->
                <div class="flex items-center justify-end gap-4 pt-1">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Limpiar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
                        style="background-color:#00405C">
                        Registrar Mascota
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
