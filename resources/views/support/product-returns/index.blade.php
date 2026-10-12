<x-layout title="Demandes de retour — Support" wide>
    <form method="GET" action="{{ route('support.product-returns.index') }}" class="flex flex-wrap items-end gap-4 text-sm">
        <div class="flex flex-col gap-1.5">
            <label for="category" class="font-medium">Catégorie</label>
            <select id="category" name="category" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 dark:border-[#3E3E3A]">
                <option value="">Toutes</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->value }}" @selected(request('category') === $category->value)>{{ $category->value }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="priority" class="font-medium">Priorité</label>
            <select id="priority" name="priority" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 dark:border-[#3E3E3A]">
                <option value="">Toutes</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" @selected(request('priority') === $priority->value)>{{ $priority->value }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="requires_human_review" class="font-medium">Revue humaine</label>
            <select id="requires_human_review" name="requires_human_review" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 dark:border-[#3E3E3A]">
                <option value="">Toutes</option>
                <option value="1" @selected(request('requires_human_review') === '1')>Requise</option>
                <option value="0" @selected(request('requires_human_review') === '0')>Non requise</option>
            </select>
        </div>

        <button type="submit" class="rounded-md bg-[#1b1b18] px-5 py-2 font-medium text-white hover:bg-black dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white">
            Filtrer
        </button>
    </form>

    @if ($errors->any())
        <ul class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-950 dark:text-red-200">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="overflow-x-auto rounded-lg bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                <tr>
                    <th class="px-4 py-3 font-medium">#</th>
                    <th class="px-4 py-3 font-medium">Produit</th>
                    <th class="px-4 py-3 font-medium">Client</th>
                    <th class="px-4 py-3 font-medium">Catégorie</th>
                    <th class="px-4 py-3 font-medium">Dommage</th>
                    <th class="px-4 py-3 font-medium">Priorité</th>
                    <th class="px-4 py-3 font-medium">Action recommandée</th>
                    <th class="px-4 py-3 font-medium">Revue humaine</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3e3e0] dark:divide-[#3E3E3A]">
                @forelse ($productReturns as $productReturn)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('product-returns.show', $productReturn) }}" class="underline underline-offset-4">{{ $productReturn->id }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $productReturn->product->name }}</td>
                        <td class="px-4 py-3">{{ $productReturn->customer_name }}</td>
                        <td class="px-4 py-3">{{ $productReturn->category?->value ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $productReturn->damage_level?->value ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $productReturn->priority?->value ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $productReturn->recommended_action?->value ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if (is_null($productReturn->requires_human_review))
                                —
                            @else
                                {{ $productReturn->requires_human_review ? 'Oui' : 'Non' }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-[#706f6c] dark:text-[#A1A09A]">Aucune demande de retour.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $productReturns->links() }}
</x-layout>
