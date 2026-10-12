<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Clavier mécanique',
                'description' => 'Clavier mécanique compact avec switches tactiles et rétroéclairage blanc.',
                'price' => 89.90,
                'is_active' => true,
            ],
            [
                'name' => 'Souris ergonomique',
                'description' => 'Souris sans fil verticale pour réduire la fatigue du poignet.',
                'price' => 49.50,
                'is_active' => true,
            ],
            [
                'name' => 'Écran 27 pouces',
                'description' => 'Moniteur IPS 27 pouces en résolution QHD, idéal pour le développement.',
                'price' => 329.00,
                'is_active' => true,
            ],
            [
                'name' => 'Casque à réduction de bruit',
                'description' => 'Casque Bluetooth avec réduction active du bruit et 30 heures d\'autonomie.',
                'price' => 199.99,
                'is_active' => true,
            ],
            [
                'name' => 'Écouteurs sans fil',
                'description' => 'Écouteurs Bluetooth intra-auriculaires avec boîtier de charge.',
                'price' => 79.00,
                'is_active' => true,
            ],
            [
                'name' => 'Support pour ordinateur portable',
                'description' => 'Support en aluminium réglable en hauteur pour ordinateur portable.',
                'price' => 39.00,
                'is_active' => true,
            ],
            [
                'name' => 'Webcam HD',
                'description' => 'Ancien modèle de webcam 720p, retiré du catalogue.',
                'price' => 24.90,
                'is_active' => false,
            ],
        ];

        foreach ([...$products, ...$this->catalogProducts()] as $product) {
            Product::query()->firstOrCreate(['name' => $product['name']], $product);
        }
    }

    /**
     * Build a deterministic catalog of product variants.
     *
     * @return list<array{name: string, description: string, price: float, is_active: bool}>
     */
    private function catalogProducts(): array
    {
        $productTypes = [
            ['name' => 'Hub USB-C', 'description' => 'Hub USB-C avec ports HDMI, USB-A et lecteur de cartes SD.', 'price' => 34.90],
            ['name' => 'Chargeur rapide', 'description' => 'Chargeur secteur GaN compatible Power Delivery.', 'price' => 29.90],
            ['name' => 'Enceinte Bluetooth', 'description' => 'Enceinte portable étanche avec 12 heures d\'autonomie.', 'price' => 59.00],
            ['name' => 'Tablette graphique', 'description' => 'Tablette à stylet sensible à la pression pour le dessin et la retouche.', 'price' => 89.00],
            ['name' => 'SSD externe', 'description' => 'Disque SSD portable USB-C au boîtier en aluminium.', 'price' => 99.00],
            ['name' => 'Microphone USB', 'description' => 'Microphone à condensateur pour podcasts et visioconférences.', 'price' => 69.90],
            ['name' => 'Lampe de bureau LED', 'description' => 'Lampe LED à intensité et température de couleur réglables.', 'price' => 44.90],
            ['name' => 'Webcam Full HD', 'description' => 'Webcam 1080p avec micro intégré et cache de confidentialité.', 'price' => 54.90],
            ['name' => 'Tapis de souris XXL', 'description' => 'Tapis de bureau grand format avec base antidérapante.', 'price' => 19.90],
            ['name' => 'Station d\'accueil', 'description' => 'Station d\'accueil double écran pour ordinateur portable.', 'price' => 149.00],
            ['name' => 'Routeur Wi-Fi 6', 'description' => 'Routeur double bande Wi-Fi 6 pour la maison et le bureau.', 'price' => 119.00],
            ['name' => 'Batterie externe', 'description' => 'Batterie externe 20 000 mAh avec charge rapide.', 'price' => 39.90],
            ['name' => 'Bras pour écran', 'description' => 'Bras articulé à ressort à gaz pour écran jusqu\'à 32 pouces.', 'price' => 79.00],
            ['name' => 'Montre connectée', 'description' => 'Montre connectée avec suivi d\'activité et notifications.', 'price' => 129.00],
            ['name' => 'Liseuse numérique', 'description' => 'Liseuse à écran 6 pouces avec éclairage réglable.', 'price' => 109.00],
        ];

        $variants = [
            ['suffix' => 'Essentiel', 'multiplier' => 0.8, 'is_active' => true],
            ['suffix' => 'Pro', 'multiplier' => 1.4, 'is_active' => true],
            ['suffix' => 'Édition 2023', 'multiplier' => 0.9, 'is_active' => false],
        ];

        $products = [];

        foreach ($productTypes as $productType) {
            foreach ($variants as $variant) {
                $products[] = [
                    'name' => "{$productType['name']} {$variant['suffix']}",
                    'description' => $productType['description'],
                    'price' => round($productType['price'] * $variant['multiplier'], 2),
                    'is_active' => $variant['is_active'],
                ];
            }
        }

        return $products;
    }
}
