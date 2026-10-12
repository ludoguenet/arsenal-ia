<x-layout title="Produits">
    <ul class="flex flex-col gap-4">
        @forelse ($products as $product)
            <li class="flex flex-col gap-3 rounded-lg bg-white p-6 text-sm shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] sm:flex-row sm:items-start sm:justify-between dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
                <div class="flex flex-col gap-1">
                    <h2 class="text-base font-semibold">{{ $product->name }}</h2>
                    <p class="text-[#706f6c] dark:text-[#A1A09A]">{{ $product->description }}</p>
                    <p class="font-medium">{{ $product->price }} €</p>
                </div>

                <a href="{{ route('product-returns.create', ['product_id' => $product->id]) }}" class="shrink-0 self-start rounded-md border border-[#19140035] px-4 py-2 font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b]">
                    Demander un retour
                </a>
            </li>
        @empty
            <li class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Aucun produit disponible.</li>
        @endforelse
    </ul>
</x-layout>
