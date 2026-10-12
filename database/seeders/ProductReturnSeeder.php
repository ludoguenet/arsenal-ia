<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductReturn;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductReturnSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * The email address identifying the ProductTriageAgent demonstration scenario.
     */
    public const string DEMO_CUSTOMER_EMAIL = 'sophie.martin@example.com';

    /**
     * The number of additional return requests generated from complaint templates.
     */
    public const int GENERATED_RETURNS_COUNT = 200;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);

        $productReturns = [
            [
                'product' => 'Casque à réduction de bruit',
                'customer_name' => 'Sophie Martin',
                'customer_email' => self::DEMO_CUSTOMER_EMAIL,
                'reason' => 'Product damaged on arrival',
                'customer_description' => 'My headphones arrived this morning. The delivery box was crushed on one corner, and the left earcup has a crack in the plastic. When I tried them, the left speaker also seemed much quieter than the right one. I would like to know if I can get a replacement.',
            ],
            [
                'product' => 'Écran 27 pouces',
                'customer_name' => 'Camille Dubois',
                'customer_email' => 'camille.dubois@example.com',
                'reason' => 'Le colis est arrivé en mauvais état.',
                'customer_description' => 'Le carton était enfoncé et déchiré sur un côté à la livraison. Je n\'ai pas encore branché l\'écran mais j\'ai peur qu\'il ait été abîmé pendant le transport.',
            ],
            [
                'product' => 'Écouteurs sans fil',
                'customer_name' => 'Thomas Leroy',
                'customer_email' => 'thomas.leroy@example.com',
                'reason' => 'L\'écouteur droit ne fonctionne plus.',
                'customer_description' => 'Achetés il y a trois semaines, jamais tombés. Depuis hier, l\'écouteur droit ne produit plus aucun son alors que le gauche fonctionne normalement. Aucune trace d\'usure visible.',
            ],
            [
                'product' => 'Support pour ordinateur portable',
                'customer_name' => 'Nicolas Lambert',
                'customer_email' => 'nicolas.lambert@example.com',
                'reason' => 'Il manque un accessoire dans le colis.',
                'customer_description' => 'Le support est en parfait état mais la boîte ne contenait pas la clé Allen indiquée dans la notice. Impossible de régler la hauteur sans elle.',
            ],
            [
                'product' => 'Souris ergonomique',
                'customer_name' => 'Julien Petit',
                'customer_email' => 'julien.petit@example.com',
                'reason' => 'La souris ne correspond pas à mes attentes.',
                'customer_description' => 'La souris fonctionne parfaitement mais la prise en main verticale ne me convient pas, je la trouve trop grande pour ma main. Utilisée deux jours, remise dans son emballage d\'origine.',
            ],
            [
                'product' => 'Clavier mécanique',
                'customer_name' => 'Laura Bernard',
                'customer_email' => 'laura.bernard@example.com',
                'reason' => 'Problème avec le clavier.',
                'customer_description' => 'Ça ne marche pas bien. Je veux le renvoyer.',
            ],
        ];

        foreach ([...$productReturns, ...$this->generatedProductReturns()] as $productReturn) {
            $product = Product::query()->active()->where('name', $productReturn['product'])->firstOrFail();

            ProductReturn::query()->firstOrCreate(
                ['customer_email' => $productReturn['customer_email']],
                [
                    'product_id' => $product->id,
                    'customer_name' => $productReturn['customer_name'],
                    'reason' => $productReturn['reason'],
                    'customer_description' => $productReturn['customer_description'],
                ],
            );
        }
    }

    /**
     * Build deterministic return requests from realistic complaint templates.
     *
     * @return list<array{product: string, customer_name: string, customer_email: string, reason: string, customer_description: string}>
     */
    private function generatedProductReturns(): array
    {
        $faker = FakerFactory::create('fr_FR');
        $faker->seed(2026);

        /** @var Collection<int, string> $productNames */
        $productNames = Product::query()->active()->orderBy('name')->pluck('name');

        $complaints = [
            ['Le colis est arrivé endommagé.', 'Le carton était écrasé à la livraison et le produit {product} présente une rayure profonde sur le dessus. Il fonctionne, mais je ne l\'accepte pas dans cet état.'],
            ['Produit cassé à la réception.', 'En ouvrant le colis, j\'ai trouvé le produit {product} avec une partie du boîtier fendue. Je ne l\'ai pas utilisé.'],
            ['Emballage abîmé, produit peut-être endommagé.', 'Le carton était mouillé et déchiré sur un côté. Je n\'ai pas encore testé le produit {product} mais je crains qu\'il ait souffert du transport.'],
            ['Le produit ne s\'allume plus.', 'J\'utilise le produit {product} depuis {weeks} semaines sans problème, mais depuis hier il ne s\'allume plus du tout, même après l\'avoir rechargé toute la nuit.'],
            ['Panne intermittente.', 'Le produit {product} se coupe tout seul plusieurs fois par jour depuis {weeks} semaines. J\'ai essayé avec un autre câble et une autre prise, sans succès.'],
            ['Fonctionnement anormal.', 'Le produit {product} chauffe beaucoup après quelques minutes d\'utilisation et dégage une légère odeur de plastique brûlé. J\'ai arrêté de l\'utiliser par précaution.'],
            ['Connexion instable.', 'La connexion du produit {product} se perd sans arrêt. J\'ai mis à jour mon ordinateur et réinitialisé l\'appareil comme indiqué dans la notice, rien n\'y fait.'],
            ['Il manque {accessory}.', 'Le colis contenait bien le produit {product}, mais il manquait {accessory}. Je ne peux pas l\'utiliser correctement sans.'],
            ['Colis incomplet.', 'La boîte du produit {product} avait déjà été ouverte et il manquait {accessory}. Le produit lui-même semble en bon état.'],
            ['Ne correspond pas à mes attentes.', 'Le produit {product} fonctionne correctement, mais il est beaucoup plus encombrant que ce que je pensais d\'après les photos. Utilisé une seule fois, remis dans son emballage.'],
            ['Qualité décevante.', 'Pour ce prix, je m\'attendais à mieux. Les finitions du produit {product} font bon marché et le rendu n\'est pas à la hauteur de la description.'],
            ['Changement d\'avis.', 'J\'ai finalement trouvé un modèle plus adapté à mon usage. Le produit {product} n\'a jamais été déballé.'],
            ['Problème avec le produit.', 'Ça ne marche pas comme prévu. Je souhaite le renvoyer.'],
            ['Je veux être remboursé.', 'Pas satisfait du produit {product}.'],
            ['Retour.', 'Voir objet.'],
        ];

        $accessories = ['le câble de charge', 'l\'adaptateur secteur', 'la notice', 'la housse de transport', 'le kit de fixation'];

        $productReturns = [];

        for ($index = 1; $index <= self::GENERATED_RETURNS_COUNT; $index++) {
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            [$reason, $description] = $faker->randomElement($complaints);

            $productName = $faker->randomElement($productNames->all());

            $replacements = [
                '{product}' => "« {$productName} »",
                '{accessory}' => $faker->randomElement($accessories),
                '{weeks}' => (string) $faker->numberBetween(1, 10),
            ];

            $productReturns[] = [
                'product' => $productName,
                'customer_name' => "{$firstName} {$lastName}",
                'customer_email' => Str::slug("{$firstName}.{$lastName}", '.')."{$index}@example.com",
                'reason' => Str::ucfirst(strtr($reason, $replacements)),
                'customer_description' => strtr($description, $replacements),
            ];
        }

        return $productReturns;
    }
}
