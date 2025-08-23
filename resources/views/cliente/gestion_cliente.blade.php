<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion de Clientes') }}
        </h2>
    </x-slot>
    <div class="flex flex-row py-10 px-20 justify-between items-center">
        <div class="">
            <label class="text-black font-bold " for="buscar">BUSCADOR:</label>
            <input type="text" id="buscar" class="border border-gray-300 bg-gray-100 rounded-lg w-50 h-10 px-2"
                placeholder="Buscar...">
            <button class="w-20 h-10 bg-indigo-400 text-white rounded-lg shadow-lg">Buscar</button>
        </div>
        <a class="w-50 h-15 text-white content-center text-center font-bold rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-0.5 shadow-md hover:shadow-lg"
            style="background-color:#00405C" href="{{ route('registrocliente') }}">
            Registrar Cliente
        </a>
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
</x-app-layout>
