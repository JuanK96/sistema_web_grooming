<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-white leading-tight">
            {{ __('Registro Cliente') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center px-20 py-6">
        <form method="POST" action="{{ route('cliente.store') }}" id="registroForm" class="space-y-6 w-full">
            @csrf
            <!-- Documento -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="tipoDocumento" class="block text-x1 font-medium text-gray-700">
                        Tipo de documento <span class="text-red-500">*</span>
                    </label>
                    <select id="tipoDocumento" name="tipoDocumento"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="Cedula de Ciudadania">Cedula de Ciudadania</option>
                        <option value="Cedula de Extranjería">Cedula de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                    </select>
                    <p id="tipoDocumentoError" class="error-message hidden">Por favor seleccione un tipo de documento
                    </p>
                </div>
                <div class="space-y-2">
                    <label for="numeroDocumento" class="block text-x1 font-medium text-gray-700">
                        Número de documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="numeroDocumento" name="numeroDocumento" pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]*"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ^0-9]/g, '').toUpperCase();"
                        maxlength="20"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        required>
                    <p id="numeroDocumentoError" class="error-message hidden">Por favor ingrese el número de documento
                    </p>
                </div>
            </div>
            <!-- Nombre -->
            <div class="space-y-2">
                <label for="nombreCompleto" class="block text-x1 font-medium text-gray-700">
                    Nombre completo <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nombreCompleto" name="nombreCompleto" pattern="[A-ZÁÉÍÓÚÑÜ\s]+"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s]/g, '').toUpperCase();"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-stone-200"
                    required>
                <p id="nombreCompletoError" class="error-message hidden">Por favor ingrese su nombre completo</p>
            </div>
            <!-- Fecha y correo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="fechaNacimiento" class="block text-x1 font-medium text-gray-700">
                        Fecha de nacimiento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="fechaNacimiento" name="fechaNacimiento"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        required>
                    <p id="fechaNacimientoError" class="error-message hidden">Por favor ingrese su fecha de nacimiento
                    </p>
                </div>
                <div class="space-y-2">
                    <label for="correoElectronico" class="block text-x1 font-medium text-gray-700">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="correoElectronico" name="correoElectronico"
                        class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                        required>
                    <p id="correoElectronicoError" class="error-message hidden">Por favor ingrese un correo válido</p>
                </div>
            </div>
            <!-- Dirección -->
            <div class="space-y-2">
                <label for="direccion" class="block text-x1 font-medium text-gray-700">
                    Dirección <span class="text-red-500">*</span>
                </label>
                <input type="text" id="direccion" name="direccion" pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]+"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                    required>
                <p id="direccionError" class="error-message hidden">Por favor ingrese su dirección</p>
            </div>
            <!-- Teléfonos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="telefono" class="block text-x1 font-medium text-gray-700">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <!-- Selector de extensión -->
                        <select id="extension" name="extension"
                            class="rounded-l-md border border-gray-300 bg-stone-200 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" selected disabled>EXT</option>
                            <option value="+57">🇨🇴 +57</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+52">🇲🇽 +52</option>
                            <option value="+34">🇪🇸 +34</option>
                            <option value="+49">🇩🇪 +49</option>
                        </select>
                        <!-- Campo de teléfono -->
                        <input type="tel" id="telefono" name="telefono" pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15"
                            class="flex-1 rounded-r-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200"
                            required>
                    </div>
                    <p id="telefonoError" class="error-message hidden">Por favor ingrese un número de teléfono</p>
                </div>
                <div class="space-y-2">
                    <label for="telefono" class="block text-x1 font-medium text-gray-700">
                        Teléfono Alterno
                    </label>
                    <div class="flex">
                        <!-- Selector de extensión -->
                        <select id="extTelAlt" name="extTelAlt"
                            class="rounded-l-md border border-gray-300 bg-stone-200 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" selected disabled>EXT</option>
                            <option value="+57">🇨🇴 +57</option>
                            <option value="+1">🇺🇸 +1</option>
                            <option value="+52">🇲🇽 +52</option>
                            <option value="+34">🇪🇸 +34</option>
                            <option value="+49">🇩🇪 +49</option>
                        </select>
                        <!-- Campo de teléfono -->
                        <input type="tel" id="telefonoAlterno" name="telefonoAlterno" pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="15"
                            class="flex-1 rounded-r-md border border-gray-300 shadow-sm py-2 px-3 bg-stone-200">
                    </div>
                    <p id="telefonoError" class="error-message hidden">Por favor ingrese un número de teléfono</p>
                </div>
            </div>
            <div class="grid grid-cols-3">
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
                <div class="flex items-center justify-end gap-4 pt-1 col-span-2">
                    <button type="reset"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Limpiar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-white rounded-md  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        style="background-color:#00405C">
                        Registrar Cliente
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
