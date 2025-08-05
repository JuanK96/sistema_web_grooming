<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
            {{ __('Registro Cliente') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center ">
            
            <form id="registroForm" class="p-6 space-y-6  w-full" >
                <!-- Documento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="tipoDocumento" class="block text-x1 font-medium text-gray-700">
                            Tipo de documento <span class="text-red-500">*</span>
                        </label>
                        <select id="tipoDocumento" name="tipoDocumento" class="mt-1 block w-full rounded-md border-black-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                            <option value="">Seleccione...</option>
                            <option value="DNI">DNI</option>
                            <option value="Carnet de Extranjería">Carnet de Extranjería</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="RUC">RUC</option>
                        </select>
                        <p id="tipoDocumentoError" class="error-message hidden">Por favor seleccione un tipo de documento</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="numeroDocumento" class="block text-x1 font-medium text-gray-700">
                            Número de documento <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="numeroDocumento" name="numeroDocumento" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                        <p id="numeroDocumentoError" class="error-message hidden">Por favor ingrese el número de documento</p>
                    </div>
                </div>
                
                <!-- Nombre -->
                <div class="space-y-2">
                    <label for="nombreCompleto" class="block text-x1 font-medium text-gray-700">
                        Nombre completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nombreCompleto" name="nombreCompleto" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                    <p id="nombreCompletoError" class="error-message hidden">Por favor ingrese su nombre completo</p>
                </div>
                
                <!-- Fecha y correo -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="fechaNacimiento" class="block text-x1 font-medium text-gray-700">
                            Fecha de nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                        <p id="fechaNacimientoError" class="error-message hidden">Por favor ingrese su fecha de nacimiento</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="correoElectronico" class="block text-x1 font-medium text-gray-700">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="correoElectronico" name="correoElectronico" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                        <p id="correoElectronicoError" class="error-message hidden">Por favor ingrese un correo válido</p>
                    </div>
                </div>
                
                <!-- Dirección -->
                <div class="space-y-2">
                    <label for="direccion" class="block text-x1 font-medium text-gray-700">
                        Dirección <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="direccion" name="direccion" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                    <p id="direccionError" class="error-message hidden">Por favor ingrese su dirección</p>
                </div>
                
                <!-- Teléfonos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="telefono" class="block text-x1 font-medium text-gray-700">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="telefono" name="telefono" class="mt-1 block w-full rounded-md border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200" required>
                        <p id="telefonoError" class="error-message hidden">Por favor ingrese un número de teléfono</p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="telefonoAlterno" class="block text-x1 font-medium text-gray-700">
                            Teléfono alterno
                        </label>
                        <input type="tel" id="telefonoAlterno" name="telefonoAlterno" class="mt-1 block w-full rounded-md border-black-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border bg-stone-200">
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-6">
                    <button type="reset" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Limpiar
                    </button>
                    <button type="submit" class="px-4 py-2 text-white rounded-md  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"style="background-color:#00405C">
                        Enviar
                    </button>
                </div>
            </form>
        
    </div>

           
        


</x-app-layout>
