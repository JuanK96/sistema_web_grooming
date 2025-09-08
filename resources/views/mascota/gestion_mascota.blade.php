<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
            {{ __('Gestion de Mascotas') }}
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
            style="background-color:#00405C" href="{{ route('registromascota') }}">
            Registrar Mascota
        </a>
    </div>
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
                        <td class="py-4 px-4 text-center">{{$mascota->id}}</td>
                        <td class="py-4 px-4 text-center">{{ $mascota->dueño->documento}}</</td>
                        <td class="py-4 px-4 text-center">{{$mascota->name}}</</td>
                        <td class="py-4 px-4 text-center">{{$mascota->edad}}</</td>
                        <td class="py-4 px-4 text-center">{{$mascota->especie}}</</td>
                        <td class="py-4 px-4 text-center">{{$mascota->sexo}}</</td>
                        <td class="py-4 px-4 text-center">{{$mascota->raza}}</</td>
                        <td class="py-4 px-4 text-center">
                            <button class="bg-green-500 text-white p-2 h-8 w-8 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M1.18164 12C2.12215 6.87976 6.60812 3 12.0003 3C17.3924 3 21.8784 6.87976 22.8189 12C21.8784 17.1202 17.3924 21 12.0003 21C6.60812 21 2.12215 17.1202 1.18164 12ZM12.0003 17C14.7617 17 17.0003 14.7614 17.0003 12C17.0003 9.23858 14.7617 7 12.0003 7C9.23884 7 7.00026 9.23858 7.00026 12C7.00026 14.7614 9.23884 17 12.0003 17ZM12.0003 15C10.3434 15 9.00026 13.6569 9.00026 12C9.00026 10.3431 10.3434 9 12.0003 9C13.6571 9 15.0003 10.3431 15.0003 12C15.0003 13.6569 13.6571 15 12.0003 15Z"></path>
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
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
