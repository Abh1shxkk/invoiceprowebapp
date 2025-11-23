<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>InvoicePro - Professional Invoicing Made Easy</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-gray-50 py-6 sm:py-12">
            <div class="absolute inset-0 bg-[url(/img/grid.svg)] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]"></div>
            <div class="relative bg-white px-6 pt-10 pb-8 shadow-xl ring-1 ring-gray-900/5 sm:mx-auto sm:max-w-lg sm:rounded-lg sm:px-10">
                <div class="mx-auto max-w-md">
                    <div class="flex items-center justify-center mb-6">
                        <h1 class="text-3xl font-bold text-blue-600">InvoicePro</h1>
                    </div>
                    <div class="divide-y divide-gray-300/50">
                        <div class="space-y-6 py-8 text-base leading-7 text-gray-600">
                            <p class="text-center text-lg font-medium text-gray-900">Professional Invoicing & Client Management</p>
                            <p class="text-center">Manage your invoices, clients, and payments with ease. Track expenses and generate reports to stay on top of your business.</p>
                            
                            <div class="flex flex-col space-y-4 mt-8">
                                @if (Route::has('login'))
                                    <div class="flex justify-center space-x-4">
                                        @auth
                                            <a href="{{ url('/dashboard') }}" class="rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Dashboard</a>
                                        @else
                                            <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Log in</a>

                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="rounded-md bg-white px-5 py-2.5 text-sm font-semibold text-blue-600 shadow-sm ring-1 ring-inset ring-blue-300 hover:bg-blue-50">Register</a>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="pt-8 text-base font-semibold leading-7">
                            <p class="text-gray-900 text-center">Ready to get started?</p>
                            <p class="text-center mt-2">
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500">Create your free account &rarr;</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
