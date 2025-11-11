<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-white leading-tight">
            {{ __('Historial de Citas') }}
        </h2>
    </x-slot>
    <div class="flex flex-col sm:flex-row sm:py-10 sm:px-20 p-3 gap-2 justify-between items-center">
        <div class="">
            <label class="text-black font-bold " for="buscar">BUSCADOR:</label>
            <form method="GET" action="{{ route('historial.search') }}" class="flex items-center space-x-2">
                <input type="text" name="search" id="buscar"
                    class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2" placeholder="Buscar...">
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
            Generar Reporte
        </button>
    </div>
    {{-- tabla --}}
    <div class="sm:mx-10 m-3 overflow-x-auto">
        <table class="min-w-full  text-xs sm:text-sm whitespace-nowrap">
            <thead class="text-white" style="background-color: #00aeef">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Documento</th>
                    <th class="py-2 px-4">Fecha</th>
                    <th class="py-2 px-4">Hora</th>
                    <th class="py-2 px-4">Groomer</th>
                    <th class="py-2 px-4">Estado</th>
                    <th class="py-2 px-4">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($citas as $cita)
                    <tr class="bg-gray-100 border-b">
                        <td class="py-4 px-4 text-center">{{ $cita->id }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->cliente->documento }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->fecha }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->hora_inicio_formateada }} -
                            {{ $cita->hora_final_formateada }}</td>
                        <td class="py-4 px-4 text-center">{{ $cita->usuario->name }}</td>
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
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $citas->links() }}
        </div>
    </div>
    {{-- generar reporte --}}
    <div id="modal"
        class="fixed inset-0  backdrop-blur-sm backdrop-opacity-100
            bg-opacity-0 hidden justify-center items-center">
        <div class="flex justify-center items-center h-full w-full">
            <div class="bg-white lg:rounded-lg p-8 w-100 lg:w-1/2 fade-in modal-contet z-50 border border-gray-300 max-h-[90vh] overflow-y-auto">
                <div class="mb-4">
                    <h2 class="text-xl font-bold">Generar Reporte</h2>
                </div>
                <form method="GET" action="{{ route('historial.reporte') }}">
                    @csrf
                    {{-- Rango de fechas --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-600 mb-1">
                                Fecha inicio:
                            </label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 bg-gray-50">
                        </div>
                        <div>
                            <label for="fecha_fin" class="block text-sm font-medium text-gray-600 mb-1">
                                Fecha fin:
                            </label>
                            <input type="date" id="fecha_fin" name="fecha_fin"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 bg-gray-50">
                        </div>
                    </div>
                    {{-- Estado de la cita --}}
                    <div class="mb-4">
                        <label for="estado" class="block text-sm font-medium text-gray-600 mb-1">
                            Estado de la cita:
                        </label>
                        <select id="estado" name="estado"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 bg-gray-50">
                            <option value="">Todos</option>
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="ATENDIDA">Atendida</option>
                            <option value="CANCELADA">Cancelada</option>
                            <option value="REPROGRAMADA">Reprogramada</option>
                        </select>
                    </div>
                    {{--Profesional (usuario que atendió la cita) --}}
                    <div class="mb-4">
                        <label for="usuario_id" class="block text-sm font-medium text-gray-600 mb-1">
                            Profesional:
                        </label>
                        <select id="usuario_id" name="usuario_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 bg-gray-50">
                            <option value="">Todos</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Cliente (por documento o nombre) --}}
                    <div class="mb-6">
                        <label for="cliente" class="block text-sm font-medium text-gray-600 mb-1">
                            Cliente:
                        </label>
                        <input type="text" id="cliente" name="cliente"
                            placeholder="Nombre o documento del cliente"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 bg-gray-50">
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="reset" id="closeModal"
                            class="px-4 py-2 bg-red-200 hover:bg-red-300 text-gray-800 rounded-lg transition duration-200">
                            Cerrar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-200 hover:bg-indigo-300 rounded-lg transition duration-200">
                            Generar
                        </button>
                    </div>
                </form>
            </div>
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
    {{-- reporte modal --}}
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
</x-app-layout>
