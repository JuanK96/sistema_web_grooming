<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-white leading-tight">
            {{ __('Gestion de Citas') }}
        </h2>
    </x-slot>
    <div class="flex flex-col sm:flex-row sm:py-10 sm:px-20 p-3 gap-2 justify-between items-center">
        <div class="">
            <label class="text-black font-bold " for="buscar">BUSCADOR:</label>
            <form method="GET" action="{{ route('cita.search') }}" class="flex items-center space-x-2">
                <input type="text" name="search" id="buscar"
                    class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2" placeholder="Buscar..."
                    pattern="[A-ZÁÉÍÓÚÑÜ\s0-9]+"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúñÑüÜ\s0-9]/g, '').toUpperCase();">
                <button class="w-20 h-10 bg-indigo-400 text-white rounded-lg shadow-lg">Buscar</button>
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
            Agendar Cita
        </button>
    </div>
    {{-- tabla --}}
    <div class="sm:mx-10 m-3 overflow-x-auto">
        <table class="min-w-full  text-xs sm:text-sm whitespace-nowrap">
            <thead class="text-white" style="background-color: #00aeef">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Documento</th>
                    <th class="py-2 px-4">Nombre</th>
                    <th class="py-2 px-4">Mascota</th>
                    <th class="py-2 px-4">Fecha</th>
                    <th class="py-2 px-4">Hora</th>
                    <th class="py-2 px-4">Estado</th>
                    <th class="py-2 px-4">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($citas as $cita)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-4 px-4 text-center">{{ $cita->id }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->cliente->documento }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->cliente->name }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->mascota->name }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->fecha }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->hora_inicio_formateada }} -
                            {{ $cita->hora_final_formateada }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->estado }}</td>
                        <td class="py-4 px-4 text-center">
                            <button
                                onclick="openVerModal(
                                        '{{ $cita->cliente->documento }}',
                                        '{{ $cita->cliente->name }}',
                                        '{{ $cita->mascota->name }}',
                                        '{{ $cita->fecha }}',
                                        '{{ $cita->hora_inicio_formateada }}',
                                        '{{ $cita->hora_final_formateada }}',
                                        '{{ $cita->estado }}',
                                        '{{ $cita->usuario->name }}',
                                        '{{ $cita->descripcion }}'
                                    )"
                                class="bg-green-500 text-white p-2 h-8 w-8 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z">
                                    </path>
                                </svg>
                            </button>
                            <button onclick="openEditModal('{{ $cita->id }}')"
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
            {{ $citas->links() }}
        </div>
    </div>
    {{-- modal agendar --}}
    <div id="modal"
        class="fixed inset-0  backdrop-blur-sm backdrop-opacity-100
            bg-opacity-0 hidden justify-center items-center">
        <div class="flex justify-center items-center h-full w-full">
            <div class="bg-white lg:rounded-lg p-8 w-100 lg:w-1/2 fade-in modal-contet z-50 border border-gray-300 max-h-[90vh] overflow-y-auto">
                <div class="mb-4">
                    <h2 class="text-xl font-bold">Agendar Cita</h2>
                </div>
                <form method="POST" action="{{ route('cita.agendar') }}">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-2">
                        <div>
                            <label for="dueño" class="block text-sm font-medium text-gray-700">Documento del
                                Dueño</label>
                            <input type="text" id="dueño" name="dueño" pattern="[0-9]*"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                                placeholder="Ej: 12345678" required
                                oninput="listarMascotas(), this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>
                        <div>
                            <label for="mascota" class="block text-sm font-medium text-gray-700">Nombre de la
                                Mascota</label>
                            <select type="text" id="mascota" name="mascota"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                                required disabled>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="grummer" class="block text-sm font-medium text-gray-700">Asignar
                            Groomer</label>
                        <select type="text" id="grummer" name="grummer"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                            required disabled>
                            <option value="" selected disabled>Seleccione un groomer...</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="relative mb-3">
                            <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" id="fecha" name="fecha"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50 text-gray-400 datepicker"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label for="horainicio" class="block text-sm font-medium text-gray-700">Hora
                                Inicio</label>
                            <select type="time" id="horainicio" name="hora_inicio"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                                    focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50 text-gray-700"
                                required>
                                <option value="" disabled selected>Seleccione una hora</option>
                            </select>
                        </div>
                        <div>
                            <label for="horafinal" class="block text-sm font-medium text-gray-700">Hora Final</label>
                            <select type="time" id="horafinal" name="hora_final"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm 
                                    focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50 text-gray-700"
                                required>
                                <option value="" disabled selected>Seleccione una hora</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="reset" id="closeModal"
                            class="px-4 py-2 bg-red-200 hover:bg-red-300 text-gray-800 rounded-lg transition duration-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-200 hover:bg-indigo-300 rounded-lg transition duration-200">
                            Agendar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Editar -->
    <div id="editModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white lg:rounded-2xl shadow-lg w-100 lg::w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Actualizar Estado</h2>
            <form id="editForm" method="POST" action="{{ route('cita.update') }}">
                @csrf
                <input class="hidden" name="idcita" id="idcita" type="text">
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="estado" name="estado"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                        required>
                        <option value="" selected disabled>Seleccione...</option>
                        <option value="ATENDIDA">Atendida</option>
                        <option value="REPROGRAMADA">Reprogramada</option>
                        <option value="CANCELADA">Cancelada</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="3" maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"
                        placeholder="Agregue una nota o detalle de la cita..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 text-white font-medium rounded-lg shadow-md"
                        style="background-color:#00405C">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
        <div class="mt-4">
            {{ $citas->links() }}
        </div>
    </div>
    <!-- Modal de ver cita -->
    <div id="verModal"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm backdrop-opacity-100 bg-opacity-0 z-50">
        <div class="bg-white lg:rounded-2xl shadow-lg w-100 lg:w-1/2 p-6 max-h-[90vh] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Detalles de la Cita</h2>
            <form>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Documento Dueño</label>
                        <input type="text" id="ver_documento" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Dueño</label>
                        <input type="text" id="ver_nombre" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mascota</label>
                        <input type="text" id="ver_mascota" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="text" id="ver_fecha" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                        <input type="text" id="ver_hora_i" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora Final</label>
                        <input type="text" id="ver_hora_f" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Groomer</label>
                        <input type="text" id="ver_grummer" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <input type="text" id="ver_estado" class="mt-1 block w-full rounded-md bg-gray-100 p-3"
                            disabled>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="ver_descripcion"
                        class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="ver_descripcion" rows="3" maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg transition bg-stone-200"disabled></textarea>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" onclick="closeVerModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
    {{-- agendar modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openModalBtn = document.getElementById('openModal');
            const closeModalBtn = document.getElementById('closeModal');
            const modal = document.getElementById('modal');
            const mascotaSelect = document.getElementById('mascota');
            const grummerSelect = document.getElementById('grummer');
            openModalBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                grummerSelect.disabled = false;
                mascotaSelect.innerHTML = '<option value="" selected disabled>Seleccione...</option>';
            });
            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>
    {{-- listar mascotas --}}
    <script>
        function listarMascotas() {
            const documento = document.getElementById('dueño').value.trim();
            const mascotaSelect = document.getElementById('mascota');
            // Estado de carga
            mascotaSelect.innerHTML = '<option value="" selected disabled>Cargando mascotas...</option>';
            // mascotaSelect.disabled = true;
            if (documento.length >= 6) {
                fetch(`/citas/obtener-mascotas?dueño=${encodeURIComponent(documento)}`)
                    .then(response => {
                        console.log('Respuesta HTTP:', response.status, response.statusText);
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Limpiar completamente el select
                        mascotaSelect.innerHTML = '';
                        if (data.length === 0) {
                            const noMascotasOption = document.createElement('option');
                            noMascotasOption.value = "";
                            noMascotasOption.textContent = "No se encontraron mascotas";
                            noMascotasOption.disabled = true;
                            noMascotasOption.selected = true;
                            mascotaSelect.appendChild(noMascotasOption);
                            mascotaSelect.disabled = true;
                        } else {
                            const defaultOption = document.createElement('option');
                            defaultOption.value = "";
                            defaultOption.textContent = "Seleccione una mascota";
                            defaultOption.disabled = true;
                            defaultOption.selected = true;
                            mascotaSelect.appendChild(defaultOption);
                            // Agregar mascotas
                            data.forEach(mascota => {
                                const option = document.createElement('option');
                                option.value = mascota.id;
                                option.textContent = mascota.name;
                                mascotaSelect.appendChild(option);
                            });
                            mascotaSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch:', error);
                        mascotaSelect.innerHTML = '';
                        const errorOption = document.createElement('option');
                        errorOption.value = "";
                        errorOption.textContent = "Error al cargar mascotas";
                        errorOption.disabled = true;
                        errorOption.selected = true;
                        mascotaSelect.appendChild(errorOption);
                    });
            } else {
                mascotaSelect.innerHTML = '<option value="" selected disabled>Ingrese al menos 6 caracteres</option>';
            }
        }
    </script>
    {{-- edit modal --}}
    <script>
        // Abrir modal de edición
        function openEditModal(id) {
            const editModal = document.getElementById('editModal');
            const estadoSelect = document.getElementById('estado');
            document.getElementById('idcita').value = id;
            // Mostrar el modal
            editModal.classList.remove('hidden');
        }
        // Cerrar modal de edición
        function closeEditModal() {
            const editModal = document.getElementById('editModal');
            editModal.classList.add('hidden');
        }
    </script>
    {{-- ver modal --}}
    <script>
        function openVerModal(documento, nombre, mascota, fecha, hora_inicio_formateada, hora_final_formateada, estado,
            groomer, descripcion) {
            document.getElementById('ver_documento').value = documento;
            document.getElementById('ver_nombre').value = nombre;
            document.getElementById('ver_mascota').value = mascota;
            document.getElementById('ver_fecha').value = fecha;
            document.getElementById('ver_hora_i').value = hora_inicio_formateada;
            document.getElementById('ver_hora_f').value = hora_final_formateada;
            document.getElementById('ver_grummer').value = groomer;
            document.getElementById('ver_estado').value = estado;
            document.getElementById('ver_descripcion').value = descripcion;
            const verModal = document.getElementById('verModal');
            verModal.classList.remove('hidden');
        }
        // Cerrar modal de ver
        function closeVerModal() {
            const verModal = document.getElementById('verModal');
            verModal.classList.add('hidden');
        }
    </script>
    {{-- obtener horas --}}
    <script>
        const fechaInput = document.getElementById('fecha');
        const groomerSelect = document.getElementById('grummer');
        const horainicio = document.getElementById('horainicio');
        const horafinal = document.getElementById('horafinal');
        let horasFinOriginal = []; // para conservar la lista completa de horas finales del backend

        function cargarHorasDisponibles() {
            const fecha = fechaInput.value;
            const groomer = groomerSelect.value.trim();
            horainicio.innerHTML = '<option disabled selected>Cargando...</option>';
            horafinal.innerHTML = '<option disabled selected>Cargando...</option>';
            if (!groomer || !fecha) {
                horainicio.innerHTML = '<option disabled selected>Seleccione groomer y fecha</option>';
                horafinal.innerHTML = '<option disabled selected>Seleccione groomer y fecha</option>';
                return;
            }
            fetch(`/citas/horas-disponibles?fecha=${encodeURIComponent(fecha)}&grummer=${encodeURIComponent(groomer)}`)
                .then(r => r.json())
                .then(data => {
                    horainicio.innerHTML = '';
                    horafinal.innerHTML = '';
                    horasFinOriginal = data.fin || [];
                    if ((!data.inicio || data.inicio.length === 0) && (!data.fin || data.fin.length === 0)) {
                        horainicio.innerHTML = '<option disabled selected>No hay horas</option>';
                        horafinal.innerHTML = '<option disabled selected>No hay horas</option>';
                        return;
                    }
                    // Rellenar horas de inicio
                    data.inicio.forEach(h => {
                        const opt = document.createElement('option');
                        opt.value = h;
                        opt.textContent = h;
                        horainicio.appendChild(opt);
                    });
                    // Rellenar horas de fin (filtradas automáticamente según primera disponible)
                    if (data.inicio.length > 0) {
                        const primeraInicio = data.inicio[0];
                        renderHorasFinal(primeraInicio);
                    }
                })
                .catch(err => {
                    console.error('Error al cargar horas:', err);
                    horainicio.innerHTML = '<option disabled selected>Error al cargar horas</option>';
                    horafinal.innerHTML = '<option disabled selected>Error al cargar horas</option>';
                });
        }
        function renderHorasFinal(horaInicioSeleccionada) {
            horafinal.innerHTML = '';
            const horaInicioDate = parseHora(horaInicioSeleccionada);
            const filtradas = horasFinOriginal.filter(horaFin => {
                const horaFinDate = parseHora(horaFin);
                return horaFinDate > horaInicioDate;
            });
            if (filtradas.length === 0) {
                horafinal.innerHTML = '<option disabled selected>No hay horas válidas</option>';
                return;
            }
            filtradas.forEach(h => {
                const opt = document.createElement('option');
                opt.value = h;
                opt.textContent = h;
                horafinal.appendChild(opt);
            });
        }
        function parseHora(horaStr) {
            // horaStr viene en formato "10:00 AM"
            const [time, meridiem] = horaStr.split(' ');
            let [hour, minute] = time.split(':').map(Number);
            if (meridiem === 'PM' && hour !== 12) hour += 12;
            if (meridiem === 'AM' && hour === 12) hour = 0;
            const date = new Date();
            date.setHours(hour, minute, 0, 0);
            return date;
        }
        // Filtrar automáticamente horas finales al elegir hora de inicio
        horainicio.addEventListener('change', function() {
            if (this.value) {
                renderHorasFinal(this.value);
            }
        });
        fechaInput.addEventListener('change', cargarHorasDisponibles);
        groomerSelect.addEventListener('change', cargarHorasDisponibles);
    </script>
</x-app-layout>
