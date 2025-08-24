<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion de Citas') }}
        </h2>
    </x-slot>
    <div class="flex flex-row py-10 px-20 justify-between items-center">
        <div class="">
            <label class="text-black font-bold " for="buscar">BUSCADOR:</label>
            <input type="text" id="buscar" class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2"
                placeholder="Buscar...">
            <button class="w-20 h-10 bg-indigo-400 text-white rounded-lg shadow-lg">Buscar</button>
        </div>
        <button id="openModal" type="submit"
            class="w-50 h-15 text-white content-center text-center font-bold rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
            style="background-color:#00405C">
            Agendar Cita
        </button>
        <div id="modal"
            class="fixed inset-0  backdrop-blur-sm backdrop-opacity-100
            bg-opacity-0 hidden justify-center items-center">
            <div class="flex justify-center items-center h-full w-full">
                <div class="bg-white rounded-lg p-8 w-1/2 fade-in modal-contet z-50 border border-gray-300">
                    <form action="">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold">Gestion Cita</h2>
                        </div>
                        <div>
                            <label for="ownerId" class="block text-sm font-medium text-gray-700">ID del Dueño</label>
                            <input type="text" id="ownerId" name="ownerId"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                                placeholder="Ej: VC123456" required>
                        </div>
                        <div>
                            <label for="petName" class="block text-sm font-medium text-gray-700">Nombre de la
                                Mascota</label>
                            <input type="text" id="petName" name="petName"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50"
                                placeholder="Ej: Firulais" required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="relative mb-3">
                                <label for="appointmentDate"
                                    class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" id="appointmentDate" name="appointmentDate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50 text-gray-400 datepicker"
                                    placeholder="Seleccione fecha" required>
                            </div>
                            <div>
                                <label for="appointmentTime"
                                    class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="text" id="appointmentTime" name="appointmentTime"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 p-3 border bg-gray-50 "
                                    placeholder="Seleccione hora" required>
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
    </div>
    <div class="px-10">
        <table class="min-w-full bg-white rounded border shadow-lg overflow-hidden">
            <thead class="text-white" style="background-color: #009600">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">IDENTIFICACION DUEÑO</th>
                    <th class="py-2 px-4">NOMBRE MASCOTA</th>
                    <th class="py-2 px-4">FECHA</th>
                    <th class="py-2 px-4">HORA</th>
                    <th class="py-2 px-4">ESTADO</th>
                    <th class="py-2 px-4">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-gray-100 border-b">
                    <td class="py-4 px-4 text-center">1</td>
                    <td class="py-4 px-4 text-center">154345671</td>
                    <td class="py-4 px-4 text-center">LUNA</td>
                    <td class="py-4 px-4 text-center">18/08/25</td>
                    <td class="py-4 px-4 text-center">3:00</td>
                    <td class="py-4 px-4 text-center">CANCELADA</td>
                    <td class="py-4 px-4 text-center">

                        <button class="bg-green-500 text-white p-2 h-8 w-8 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="110%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M14 14.252V22H4C4 17.5817 7.58172 14 12 14C12.6906 14 13.3608 14.0875 14 14.252ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM18 17V14H20V17H23V19H20V22H18V19H15V17H18Z">
                                </path>
                            </svg>
                        </button>
                        <button class="bg-yellow-500 text-white p-2 h-8 w-8 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="110%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M21 15.2426V21.0082C21 21.556 20.5551 22 20.0066 22H3.9934C3.44476 22 3 21.5511 3 20.9925V9H9C9.55228 9 10 8.55228 10 8V2H20.0017C20.5531 2 21 2.45531 21 2.9918V6.75736L12.0012 15.7562L11.995 19.995L16.2414 20.0012L21 15.2426ZM21.7782 8.80761L23.1924 10.2218L15.4142 18L13.9979 17.9979L14 16.5858L21.7782 8.80761ZM3 7L8 2.00318V7H3Z">
                                </path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <!-- Más filas se pueden agregar aquí -->
            </tbody>
        </table>
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
