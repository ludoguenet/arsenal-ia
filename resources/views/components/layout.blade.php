@props(['title', 'wide' => false])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title }} · {{ config('app.name', 'Laravel') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#FDFDFC] p-6 text-[#1b1b18] lg:p-8 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <main @class(['mx-auto flex w-full flex-col gap-6', 'max-w-2xl' => ! $wide, 'max-w-5xl' => $wide])>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('products.list') }}" class="underline-offset-4 hover:underline">Produits</a>
                <a href="{{ route('product-returns.create') }}" class="underline-offset-4 hover:underline">Demande de retour</a>
                <a href="{{ route('support.product-returns.index') }}" class="underline-offset-4 hover:underline">Support</a>
            </nav>

            <h1 class="text-2xl font-semibold">{{ $title }}</h1>

            @if (session('status'))
                <p class="rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-900 dark:bg-green-950 dark:text-green-200">
                    {{ session('status') }}
                </p>
            @endif

            {{ $slot }}
        </main>
    </body>
</html>
