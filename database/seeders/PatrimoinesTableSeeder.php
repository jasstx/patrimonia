<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patrimoine;
use App\Models\Categorie;
use Carbon\Carbon;

class PatrimoinesTableSeeder extends Seeder
{
    public function run()
    {
        $elements = [];

        // ===============================
        // 1. Catégorie CPNU
        // ===============================
        $cpnu = Categorie::where('initiale', 'CPNU')->first();
        $elementsCPNU = [
            "Rites et cérémonies liés aux événements de la vie",
            "Festivals et fêtes traditionnelles",
            "Connaissances et pratiques liées à la nature",
            "Savoirs liés à l'art culinaire",
            "Expressions orales et contes",
            "Danses traditionnelles",
            "Chants traditionnels",
            "Musique traditionnelle",
            "Médecine traditionnelle",
            "Rites initiatiques",
            "Artisanat traditionnel",
            "Architecture traditionnelle",
            "Savoir-faire agricoles",
            "Jeux traditionnels",
            "Techniques de chasse et de pêche",
            "Costumes traditionnels",
            "Bijoux traditionnels",
            "Peintures corporelles",
            "Masques traditionnels",
            "Objets rituels",
            "Symboles et signes traditionnels",
            "Langues locales",
            "Systèmes de parenté",
            "Pratiques religieuses traditionnelles",
            "Coutumes liées à la naissance",
            "Coutumes liées au mariage",
            "Coutumes liées à la mort",
            "Chants de guerre",
            "Danses guerrières",
            "Chants de travail",
            "Danses de travail",
            "Chants de divertissement",
            "Danses de divertissement",
            "Chants de louange",
            "Danses de louange",
            "Contes initiatiques",
            "Proverbes",
            "Devine",
            "Énigmes",
            "Histoires épiques",
            "Récits mythologiques",
            "Légendes locales",
            "Héros traditionnels",
            "Rituels de guérison",
            "Plantes médicinales",
            "Techniques de tissage",
            "Teinture traditionnelle",
            "Poterie",
            "Sculpture sur bois",
            "Ferronnerie",
            "Orfèvrerie",
            "Couture traditionnelle",
            "Construction de cases",
            "Architecture en banco",
            "Peinture murale",
            "Peinture sur tissu",
            "Tatouage traditionnel",
            "Scarification",
            "Coiffure traditionnelle",
            "Ornements corporels",
            "Pratiques de solidarité",
            "Systèmes de chefferie",
            "Pratiques de médiation",
            "Pratiques de justice traditionnelle",
            "Pratiques de divination",
            "Astrologie traditionnelle",
            "Calendriers traditionnels",
            "Systèmes de mesure traditionnels",
            "Jeux de société traditionnels",
            "Sports traditionnels",
            "Arts martiaux traditionnels",
            "Techniques de survie",
            "Pratiques agricoles saisonnières",
            "Croyances liées à la nature",
        ];

        foreach ($elementsCPNU as $index => $nom) {
            $numero = $index + 1;
            $elements[] = [
                'nom' => $nom,
                'initiale' => 'CPNU-' . $numero,
                'domaine' => 'CPNU',            // ✅ correction
                'numero_element' => $numero,    // ✅ correction
                'description' => $nom . ' - Patrimoine culturel immatériel du Burkina Faso',
                'status' => 'inscrit',
                'date_inscription' => Carbon::now()->subYears(rand(1, 10)),
                'localisation' => 'Burkina Faso',
                'region' => $this->getRandomRegion(),
                'id_categorie' => $cpnu->id_categorie,
                'historique' => 'Pratique traditionnelle transmise de génération en génération',
                'caracteristiques' => 'Savoir-faire ancestral lié à la nature et à la médecine traditionnelle',
                'est_urgent' => rand(0, 1) == 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // ===============================
        // 2. Catégorie PSREF
        // ===============================
        $psref = Categorie::where('initiale', 'PSREF')->first();
        $elementsPSREF = [
            "Danse traditionnelle Warba",
            "Musique traditionnelle balafon",
            "Chants funéraires",
            "Art de la forge traditionnelle",
            "Savoir-faire en tissage traditionnel",
            "Médecine traditionnelle par les plantes",
            "Rites de passage à l'âge adulte",
            "Fête des masques",
            "Jeu traditionnel du Langa",
            "Techniques traditionnelles de pêche",
            "Pratiques de chasse collective",
            "Fabrication de poterie",
            "Construction traditionnelle en terre",
            "Scarification rituelle",
            "Contes et récits initiatiques",
            "Proverbes Mossi",
            "Coutumes funéraires Gourounsi",
            "Langue et traditions Bissa",
            "Techniques agricoles Dogon",
            "Art de la sculpture sur bois",
            "Rites de fécondité",
            "Fête des moissons",
            "Rituels de guérison par la danse",
            "Cérémonies de mariage traditionnelles",
            "Costumes rituels Bobo",
            "Masques Senoufo",
            "Danses de guerre traditionnelles",
            "Chants de travail collectif",
            "Instruments de musique traditionnelle",
            "Fête des récoltes",
            "Ornements corporels",
            "Coiffures traditionnelles",
            "Peintures corporelles rituelles",
            "Jeux traditionnels de société",
            "Lutte traditionnelle",
            "Rites d’initiation Mossi",
            "Fête des ancêtres",
            "Divination traditionnelle",
            "Calendrier agricole",
            "Pratiques de solidarité communautaire",
            "Transmission orale des savoirs",
            "Chants de louange"
        ];

        foreach ($elementsPSREF as $index => $nom) {
            $numero = $index + 1;
            $elements[] = [
                'nom' => $nom,
                'initiale' => 'PSREF-' . $numero,
                'domaine' => 'PSREF',
                'numero_element' => $numero,
                'description' => $nom . ' - Patrimoine spécifique représentatif du Burkina Faso',
                'status' => 'inscrit',
                'date_inscription' => Carbon::now()->subYears(rand(1, 10)),
                'localisation' => 'Burkina Faso',
                'region' => $this->getRandomRegion(),
                'id_categorie' => $psref->id_categorie,
                'historique' => 'Pratique culturelle spécifique transmise de génération en génération',
                'caracteristiques' => 'Savoir-faire représentatif du patrimoine burkinabè',
                'est_urgent' => rand(0, 1) == 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // ===============================
        // 3. Catégorie ADS
        // ===============================
        $ads = Categorie::where('initiale', 'ADS')->first();
        $elementsADS = [
            "Technique traditionnelle de fabrication du beurre de karité",
            "Fabrication du dolo (bière traditionnelle)",
            "Tissage du coton artisanal",
            "Fabrication de paniers en paille",
            "Savoir-faire en teinture traditionnelle",
            "Fabrication des tambours traditionnels",
            "Art de la vannerie",
            "Fabrication de bijoux en bronze",
            "Forgerons traditionnels",
            "Fabrication de calebasses décorées",
            "Peinture traditionnelle sur tissu",
            "Fabrication de sandales en cuir",
            "Fabrication de chapeaux traditionnels",
            "Sculpture sur pierre",
            "Fabrication de pipes traditionnelles",
            "Construction de greniers traditionnels",
            "Fabrication de lits traditionnels",
            "Fabrication d’armes traditionnelles",
            "Art de la maroquinerie",
            "Fabrication de nattes",
            "Fabrication de poupées traditionnelles",
            "Fabrication de mortiers et pilons",
            "Art de la broderie traditionnelle",
            "Fabrication de cornes d’appel",
            "Fabrication de flûtes traditionnelles",
            "Fabrication de xylophones",
            "Fabrication de bracelets en cuivre",
            "Fabrication de boucliers traditionnels",
            "Fabrication de lances traditionnelles"
        ];

        foreach ($elementsADS as $index => $nom) {
            $numero = $index + 1;
            $elements[] = [
                'nom' => $nom,
                'initiale' => 'ADS-' . $numero,
                'domaine' => 'ADS',
                'numero_element' => $numero,
                'description' => $nom . ' - Artisanat traditionnel du Burkina Faso',
                'status' => 'inscrit',
                'date_inscription' => Carbon::now()->subYears(rand(1, 10)),
                'localisation' => 'Burkina Faso',
                'region' => $this->getRandomRegion(),
                'id_categorie' => $ads->id_categorie,
                'historique' => 'Savoir-faire artisanal transmis de génération en génération',
                'caracteristiques' => 'Connaissances liées à l’artisanat et aux techniques traditionnelles',
                'est_urgent' => rand(0, 1) == 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // ===============================
        // 4. Catégorie SFAT
        // ===============================
        $sfat = Categorie::where('initiale', 'SFAT')->first();
        $elementsSFAT = [
            "Balafon pentatonique des Sénoufo",
            "Xylophone traditionnel",
            "Kora traditionnelle",
            "Flûte peule",
            "Tamtam parleur",
            "Chants rituels Bobo",
            "Danses traditionnelles Mossi",
            "Chants de louange des griots",
            "Musique des funérailles Gourmantché",
            "Danses de chasseurs",
            "Chants des pêcheurs",
            "Danse des masques Bwa",
            "Musique de mariage Bissa",
            "Chants de travail collectif",
            "Danses de réjouissance",
            "Musique des fêtes de moisson",
            "Chants d’enfants",
            "Danses de cour royale",
            "Chants religieux traditionnels",
            "Musique des cérémonies initiatiques",
            "Danses de circoncision",
            "Chants de guérison",
            "Danses de possession",
            "Musique des devins",
            "Chants de bénédiction",
            "Danses de réjouissance funéraire",
            "Musique de transe",
            "Chants de guerre",
            "Danses de guerre",
            "Musique des conteurs",
            "Chants des anciens",
            "Danses de fertilité",
            "Chants des femmes",
            "Danses de femmes",
            "Chants de naissance",
            "Danses de naissance",
            "Chants royaux",
            "Danses royales",
            "Chants de sagesse",
            "Danses initiatiques",
            "Chants de griots",
            "Danses de griots",
            "Chants d’épopée",
            "Danses d’épopée"
        ];

        foreach ($elementsSFAT as $index => $nom) {
            $numero = $index + 1;
            $elements[] = [
                'nom' => $nom,
                'initiale' => 'SFAT-' . $numero,
                'domaine' => 'SFAT',
                'numero_element' => $numero,
                'description' => $nom . ' - Savoirs et expressions du folklore artistique traditionnel',
                'status' => 'inscrit',
                'date_inscription' => Carbon::now()->subYears(rand(1, 10)),
                'localisation' => 'Burkina Faso',
                'region' => $this->getRandomRegion(),
                'id_categorie' => $sfat->id_categorie,
                'historique' => 'Pratique artistique transmise oralement',
                'caracteristiques' => 'Musique, danses et expressions folkloriques traditionnelles',
                'est_urgent' => rand(0, 1) == 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // ===============================
        // 5. Catégorie TEO
        // ===============================
        $teo = Categorie::where('initiale', 'TEO')->first();
        $elementsTEO = [
            "Site sacré de Tiébélé",
            "Grottes de Douna"
        ];

        foreach ($elementsTEO as $index => $nom) {
            $numero = $index + 1;
            $elements[] = [
                'nom' => $nom,
                'initiale' => 'TEO-' . $numero,
                'domaine' => 'TEO',
                'numero_element' => $numero,
                'description' => $nom . ' - Tradition et espace oral du Burkina Faso',
                'status' => 'inscrit',
                'date_inscription' => Carbon::now()->subYears(rand(1, 10)),
                'localisation' => 'Burkina Faso',
                'region' => $this->getRandomRegion(),
                'id_categorie' => $teo->id_categorie,
                'historique' => 'Lieu et espace de transmission orale',
                'caracteristiques' => 'Tradition et espace de mémoire collective',
                'est_urgent' => rand(0, 1) == 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // ===============================
        // Insertion finale
        // ===============================
        Patrimoine::insert($elements);
    }

    private function getRandomRegion()
    {
        $regions = [
            'Boucle du Mouhoun',
            'Cascades',
            'Centre',
            'Centre-Est',
            'Centre-Nord',
            'Centre-Ouest',
            'Centre-Sud',
            'Est',
            'Hauts-Bassins',
            'Nord',
            'Plateau-Central',
            'Sahel',
            'Sud-Ouest'
        ];
        return $regions[array_rand($regions)];
    }
}
