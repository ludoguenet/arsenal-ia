<x-layout title="Demande de retour #{{ $productReturn->id }}">
    <dl class="grid gap-4 rounded-lg bg-white p-6 text-sm shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] sm:grid-cols-[12rem_1fr] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
        <dt class="font-medium">Produit</dt>
        <dd>{{ $productReturn->product->name }} — {{ $productReturn->product->price }} €</dd>

        <dt class="font-medium">Client</dt>
        <dd>{{ $productReturn->customer_name }} &lt;{{ $productReturn->customer_email }}&gt;</dd>

        <dt class="font-medium">Motif du retour</dt>
        <dd class="whitespace-pre-line">{{ $productReturn->reason }}</dd>

        <dt class="font-medium">Description du client</dt>
        <dd class="whitespace-pre-line">{{ $productReturn->customer_description }}</dd>

        <dt class="font-medium">Soumise le</dt>
        <dd>{{ $productReturn->created_at->format('d/m/Y H:i') }}</dd>
    </dl>

    <form action="{{ route('product-returns.triage', ['return' => $productReturn]) }}" method="POST">
        @csrf
        <input type="submit" value="Call the Agent for triage" class="bg-green-200 px-4 py-2 rounded cursor-pointer">
    </form>

    <section class="flex flex-col gap-4 rounded-lg bg-white p-6 text-sm shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">


        <h2 class="text-lg font-semibold">Triage</h2>

        <dl class="grid gap-4 sm:grid-cols-[12rem_1fr]">
            <dt class="font-medium">Catégorie</dt>
            <dd>{{ $productReturn->category?->value ?? 'Non classée' }}</dd>

            <dt class="font-medium">Niveau de dommage</dt>
            <dd>{{ $productReturn->damage_level?->value ?? 'Non évalué' }}</dd>

            <dt class="font-medium">Priorité</dt>
            <dd>{{ $productReturn->priority?->value ?? 'Non définie' }}</dd>

            <dt class="font-medium">Résumé</dt>
            <dd class="whitespace-pre-line">{{ $productReturn->triage_summary ?? 'Aucun résumé disponible.' }}</dd>

            <dt class="font-medium">Action recommandée</dt>
            <dd>{{ $productReturn->recommended_action?->value ?? 'Aucune' }}</dd>

            <dt class="font-medium">Revue humaine</dt>
            <dd>
                @if (is_null($productReturn->requires_human_review))
                    Non déterminée
                @else
                    {{ $productReturn->requires_human_review ? 'Requise' : 'Non requise' }}
                @endif
            </dd>

            <dt class="font-medium">Triée le</dt>
            <dd>{{ $productReturn->triaged_at?->format('d/m/Y H:i') ?? 'Pas encore triée' }}</dd>
        </dl>
    </section>
</x-layout>
