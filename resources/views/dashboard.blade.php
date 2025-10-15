<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    </head>
        <div class=" p-6 flex-grow">
            <div class = " rounded-full  w-full inline-block text-center p-6 mb-6" style="background-color: #009600">
                <h1 class="text-white font-bold text-3xl ">BIENVENIDO {{ explode(' ', Auth::user()->name)[0] }}</h1>
                <p class= "text-white italic">Gracias por tu trabajo y compromiso </p>
            </div>
            {{-- gestion de clientes --}}
            <div class="flex overflow-x-auto gap-4 pb-4 justify-center">
                <div class="border border-gray-300 rounded-lg p-3 w-62 bg-white shadow-lg cursor-pointer ">
                    <a class="w-auto" href="{{ route('gestioncliente') }}">
                        <div class="hover:scale-105 h-32 w-auto rounded-t-lg flex items-center justify-center"style="background-color:#00405c"">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M14 14.252V16.3414C13.3744 16.1203 12.7013 16 12 16C8.68629 16 6 18.6863 6 22H4C4 17.5817 7.58172 14 12 14C12.6906 14 13.3608 14.0875 14 14.252ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM18 17V14H20V17H23V19H20V22H18V19H15V17H18Z ">
                                </path>
                            </svg>
                        </div>
                        <p class="mt-4 font-semibold text-center">Gestion de Clientes</p>
                    </a>
                </div>
                {{-- gestion de mascotas --}}
                <div class="border border-gray-300 rounded-lg p-3 w-62 bg-white shadow-lg cursor-pointer">
                    <a class="w-auto" href="{{ route('gestionmascota') }}">
                        <div class="hover:scale-105 h-32 w-auto rounded-t-lg flex items-center justify-center " style="background-color: #009600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M7.56331 19.2798C7.73337 19.2876 7.92984 19.2906 8.14727 19.2863C8.77279 19.2739 9.4239 19.2047 10.059 19.0626C11.0716 18.838 12.0771 18.8143 13.0395 18.9318C13.5864 18.9986 14.0247 19.0969 14.3275 19.1891C15.4052 19.4638 16.9386 19.4119 17.3331 18.7782C17.6238 18.3113 17.5856 17.9916 17.3071 17.5803C17.2466 17.4909 17.1809 17.4105 17.1185 17.3445C17.1097 17.3351 17.1016 17.3269 17.0945 17.3197C17.0282 17.267 16.9917 17.2377 16.9302 17.1878C16.7544 17.0449 16.5599 16.8823 16.3513 16.702C15.7559 16.1875 15.1602 15.6296 14.5987 15.0426C13.9929 14.4095 13.461 13.7777 13.0219 13.1489L12.9861 13.0958C12.2441 11.9571 11.428 12.029 10.9837 12.7785C10.7019 13.254 10.3821 13.696 10.029 14.1105C9.61853 14.5924 9.19882 15.0001 8.72348 15.4067C8.60056 15.5119 8.22086 15.8269 8.31171 15.751C8.30802 15.7541 8.29484 15.7661 8.36265 15.6797C8.26441 15.8004 7.41258 16.5574 7.16177 16.8417C6.64726 17.4248 6.44014 17.9224 6.51725 18.3224C6.58965 18.6978 6.73623 18.9089 6.96754 19.0667C7.08515 19.1469 7.22108 19.2086 7.35634 19.2494C7.39091 19.2598 7.42153 19.2677 7.44674 19.2732C7.48007 19.2755 7.51904 19.2777 7.56331 19.2798ZM14.6617 12.0039C16.0373 13.9739 18.3933 15.7967 18.3933 15.7967C18.3933 15.7967 20.4569 17.5447 19.031 19.8352C17.605 22.1257 13.778 21.1127 13.778 21.1127C13.778 21.1127 12.2578 20.6236 10.492 21.0152C8.72706 21.4102 7.20561 21.2604 7.20561 21.2604C7.20561 21.2604 4.99964 21.0152 4.55343 18.701C4.10721 16.3869 6.61326 14.6608 6.81143 14.4173C7.00657 14.1699 8.32336 13.3442 9.26328 11.7587C10.2032 10.1731 12.8471 9.21909 14.6617 12.0039ZM20.2002 10.5846C20.2002 11.0415 20.391 12.9772 18.6478 13.0158C16.9053 13.0544 16.8317 11.8378 16.8317 10.9662C16.8317 10.053 17.0192 8.76113 18.422 8.76113C19.8225 8.76113 20.2002 10.13 20.2002 10.5846ZM14.7711 7.80669C13.591 7.65486 13.3238 6.58499 13.4378 5.51446C13.5333 4.63866 14.5804 3.2953 15.419 3.48769C16.2559 3.6784 17.0188 4.78748 16.8646 5.74238C16.7134 6.69862 15.9533 7.9602 14.7711 7.80669ZM9.75498 7.43996C8.89373 7.43996 8.19433 6.44695 8.19433 5.21916C8.19433 3.99269 8.8934 3 9.75498 3C10.6179 3 11.3153 3.99269 11.3153 5.21916C11.3153 6.44695 10.6179 7.43996 9.75498 7.43996ZM5.96186 12.0061C4.26673 12.3714 3.63542 10.4091 3.82144 9.49078C3.82144 9.49078 4.02239 7.50401 5.39776 7.38059C6.49074 7.28599 7.29562 8.4821 7.37884 9.16641C7.42971 9.61029 7.66125 11.6412 5.96186 12.0061Z">
                                </path>
                            </svg>
                        </div>
                        <p class="mt-4 font-semibold text-center">Gestion de Mascotas</p>
                    </a>
                </div>
                {{-- gestion de citas --}}
                <div class="border border-gray-300 rounded-lg p-3 w-62 bg-white shadow-lg cursor-pointer">
                    <a class="w-auto" href="{{ route('gestioncita') }}">
                        <div class="hover:scale-105 h-32 w-auto rounded-t-lg flex items-center justify-center "style="background-color: #00405c">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M9 1V3H15V1H17V3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H7V1H9ZM20 11H4V19H20V11ZM8 14V16H6V14H8ZM18 14V16H10V14H18ZM7 5H4V9H20V5H17V7H15V5H9V7H7V5Z">
                                </path>
                            </svg>
                        </div>
                        <p class="mt-4 font-semibold text-center">Gestion De Citas</p>
                    </a>
                </div>
                @if (Auth::user()->id_role != 3)
                    {{-- historial de citas --}}
                <div class="border border-gray-300 rounded-lg p-3 w-62 bg-white shadow-lg cursor-pointer">
                    <a class="w-auto" href="{{ route('gestionhistorial') }}">
                        <div class="hover:scale-105 h-32 w-auto rounded-t-lg flex items-center justify-center "style="background-color: #009600"">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50%"
                                fill="rgba(255,255,255,1)">
                                <path
                                    d="M12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12H4C4 16.4183 7.58172 20 12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C9.25022 4 6.82447 5.38734 5.38451 7.50024L8 7.5V9.5H2V3.5H4L3.99989 5.99918C5.82434 3.57075 8.72873 2 12 2ZM13 7L12.9998 11.585L16.2426 14.8284L14.8284 16.2426L10.9998 12.413L11 7H13Z">
                                </path>
                            </svg>
                            </svg>
                        </div>
                        <p class="mt-4 font-semibold text-center">Historial de Citas</p>
                    </a>
                </div>
                @endif
                @if (Auth::user()->id_role == 1)
                {{-- gestion de usuarios --}}
                <div class="border border-gray-300 rounded-lg p-3 w-62 bg-white shadow-lg cursor-pointer">
                    <a class="w-auto" href="{{ route('gestionusuario') }}">
                        <div class="hover:scale-105 h-32 w-auto rounded-t-lg flex items-center justify-center "style="background-color: #00405c">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                width="50%" " fill="rgba(255,255,255,1)"><path d=" M2 22C2 17.5817 5.58172 14 10
                                14C14.4183 14 18 17.5817 18 22H16C16 18.6863 13.3137 16 10 16C6.68629 16 4 18.6863 4
                                22H2ZM10 13C6.685 13 4 10.315 4 7C4 3.685 6.685 1 10 1C13.315 1 16 3.685 16 7C16 10.315
                                13.315 13 10 13ZM10 11C12.21 11 14 9.21 14 7C14 4.79 12.21 3 10 3C7.79 3 6 4.79 6 7C6
                                9.21 7.79 11 10 11ZM18.2837 14.7028C21.0644 15.9561 23 18.752 23 22H21C21 19.564 19.5483
                                17.4671 17.4628 16.5271L18.2837 14.7028ZM17.5962 3.41321C19.5944 4.23703 21 6.20361 21
                                8.5C21 11.3702 18.8042 13.7252 16 13.9776V11.9646C17.6967 11.7222 19 10.264 19 8.5C19
                                7.11935 18.2016 5.92603 17.041 5.35635L17.5962 3.41321Z"></path></svg>
                        </div>
                        <p class="mt-4 font-semibold text-center">Gestion De Usuarios</p>
                    </a>
                </div>
                @endif
            </div>
        </div>
</x-app-layout>
