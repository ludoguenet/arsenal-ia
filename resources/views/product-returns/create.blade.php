<x-layout title="Demande de retour">
    <form method="POST" action="{{ route('product-returns.store') }}" class="flex flex-col gap-5 rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
        @csrf

        <div class="flex flex-col gap-1.5">
            <label for="product_id" class="text-sm font-medium">Produit</label>
            <select id="product_id" name="product_id" required class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 text-sm dark:border-[#3E3E3A]">
                <option value="">Sélectionnez un produit</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id', request('product_id')) == $product->id)>{{ $product->name }}</option>
                @endforeach
            </select>
            @error('product_id')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="customer_name" class="text-sm font-medium">Nom</label>
            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required maxlength="255" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 text-sm dark:border-[#3E3E3A]">
            @error('customer_name')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="customer_email" class="text-sm font-medium">Adresse e-mail</label>
            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" required maxlength="255" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 text-sm dark:border-[#3E3E3A]">
            @error('customer_email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="reason" class="text-sm font-medium">Motif du retour</label>
            <textarea id="reason" name="reason" rows="3" required maxlength="5000" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 text-sm dark:border-[#3E3E3A]">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="customer_description" class="text-sm font-medium">État du produit et circonstances</label>
            <textarea id="customer_description" name="customer_description" rows="5" required maxlength="5000" class="rounded-md border border-[#e3e3e0] bg-transparent px-3 py-2 text-sm dark:border-[#3E3E3A]">{{ old('customer_description') }}</textarea>
            @error('customer_description')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="self-start rounded-md bg-[#1b1b18] px-5 py-2 text-sm font-medium text-white hover:bg-black dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white">
            Envoyer la demande
        </button>
    </form>
</x-layout>
