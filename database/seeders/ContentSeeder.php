<?php

namespace Database\Seeders;

use App\Models\Chapitre;
use App\Models\Lecon;
use App\Models\Niveau;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Programme de mathématiques du lycée marocain.
     *
     * Le contenu des leçons sert de matière première à la génération de quiz
     * par l'IA : il doit rester dense et exact.
     */
    public function run(): void
    {
        foreach ($this->programme() as $ordreNiveau => $niveauData) {
            $niveau = Niveau::firstOrCreate(
                ['nom' => $niveauData['nom']],
                ['ordre' => $ordreNiveau + 1],
            );

            foreach ($niveauData['chapitres'] as $ordreChapitre => $chapitreData) {
                $chapitre = Chapitre::firstOrCreate(
                    ['titre' => $chapitreData['titre'], 'id_niveau' => $niveau->id],
                    [
                        'description'  => $chapitreData['description'],
                        'ordre'        => $ordreChapitre + 1,
                        'is_published' => true,
                    ],
                );

                foreach ($chapitreData['lecons'] as $ordreLecon => $leconData) {
                    Lecon::firstOrCreate(
                        ['titre' => $leconData['titre'], 'id_chapitre' => $chapitre->id],
                        [
                            'contenu'      => $leconData['contenu'],
                            'ordre'        => $ordreLecon + 1,
                            'is_published' => true,
                        ],
                    );
                }
            }
        }
    }

    private function programme(): array
    {
        return [
            [
                'nom' => 'Tronc Commun',
                'chapitres' => [
                    [
                        'titre' => 'Les ensembles de nombres',
                        'description' => "Découvrir les ensembles N, Z, D, Q et R, et savoir situer un nombre dans la bonne famille.",
                        'lecons' => [
                            [
                                'titre' => 'Les ensembles N, Z, D, Q et R',
                                'contenu' => <<<'TXT'
                                    On distingue cinq ensembles de nombres emboîtés les uns dans les autres :

                                    - N est l'ensemble des entiers naturels : 0, 1, 2, 3, ...
                                    - Z est l'ensemble des entiers relatifs : ..., -2, -1, 0, 1, 2, ...
                                    - D est l'ensemble des nombres décimaux, c'est-à-dire ceux qui s'écrivent a/10^n avec a dans Z et n dans N.
                                    - Q est l'ensemble des nombres rationnels, ceux qui s'écrivent a/b avec a dans Z et b dans Z*.
                                    - R est l'ensemble des nombres réels, qui contient en plus les irrationnels.

                                    On a la chaîne d'inclusions : N ⊂ Z ⊂ D ⊂ Q ⊂ R.

                                    Un nombre est rationnel si et seulement si son écriture décimale est finie ou périodique.
                                    Par exemple 1/3 = 0,333... est périodique donc rationnel.

                                    Les nombres irrationnels ne peuvent pas s'écrire sous forme de fraction. Les exemples
                                    classiques sont √2, √3, π et e. La démonstration de l'irrationalité de √2 se fait par
                                    l'absurde : on suppose √2 = a/b avec la fraction irréductible, et on aboutit à une
                                    contradiction car a et b seraient tous deux pairs.
                                    TXT,
                            ],
                            [
                                'titre' => 'Intervalles et valeur absolue',
                                'contenu' => <<<'TXT'
                                    Un intervalle de R est un sous-ensemble « sans trou ». On note :
                                    - [a ; b] l'intervalle fermé : l'ensemble des x tels que a ≤ x ≤ b.
                                    - ]a ; b[ l'intervalle ouvert : l'ensemble des x tels que a < x < b.
                                    - [a ; +∞[ l'ensemble des x tels que x ≥ a.

                                    La valeur absolue d'un réel x, notée |x|, vaut x si x ≥ 0 et -x si x < 0.
                                    Elle représente la distance de x à 0 sur la droite numérique.

                                    Propriétés fondamentales :
                                    - |x| ≥ 0 pour tout réel x, et |x| = 0 équivaut à x = 0.
                                    - |x × y| = |x| × |y|.
                                    - |x + y| ≤ |x| + |y| : c'est l'inégalité triangulaire.
                                    - |x - a| représente la distance entre x et a.

                                    Résolution d'équations : |x| = k (avec k > 0) équivaut à x = k ou x = -k.
                                    Résolution d'inéquations : |x - a| ≤ r équivaut à a - r ≤ x ≤ a + r,
                                    c'est-à-dire x appartient à l'intervalle [a - r ; a + r].
                                    TXT,
                            ],
                        ],
                    ],
                    [
                        'titre' => 'Calcul vectoriel dans le plan',
                        'description' => "Manipuler les vecteurs, la colinéarité et les coordonnées dans un repère.",
                        'lecons' => [
                            [
                                'titre' => 'Vecteurs et opérations',
                                'contenu' => <<<'TXT'
                                    Un vecteur est défini par une direction, un sens et une longueur (sa norme).
                                    Le vecteur AB va du point A vers le point B.

                                    Égalité : deux vecteurs AB et CD sont égaux lorsque ABDC est un parallélogramme.

                                    Somme de vecteurs — relation de Chasles : AB + BC = AC.
                                    Cette relation est l'outil principal pour simplifier une expression vectorielle.

                                    Multiplication par un réel : si k est un réel et u un vecteur, alors k×u est un
                                    vecteur de même direction que u. Son sens est celui de u si k > 0, opposé si k < 0,
                                    et sa norme vaut |k| × ||u||.

                                    Colinéarité : deux vecteurs u et v (avec v non nul) sont colinéaires s'il existe un
                                    réel k tel que u = k×v. Trois points A, B et C sont alignés si et seulement si les
                                    vecteurs AB et AC sont colinéaires.

                                    Dans un repère, si u a pour coordonnées (x ; y) et v pour coordonnées (x' ; y'),
                                    alors u et v sont colinéaires si et seulement si leur déterminant est nul :
                                    x×y' - y×x' = 0.
                                    TXT,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'nom' => '1ère Année Bac',
                'chapitres' => [
                    [
                        'titre' => 'Suites numériques',
                        'description' => "Étudier les suites arithmétiques et géométriques, leur sens de variation et leurs sommes.",
                        'lecons' => [
                            [
                                'titre' => 'Suites arithmétiques et géométriques',
                                'contenu' => <<<'TXT'
                                    Une suite (Un) est une fonction définie sur N qui à chaque entier n associe un réel Un.

                                    SUITE ARITHMÉTIQUE
                                    Une suite est arithmétique de raison r si, pour tout n, on a U(n+1) = Un + r.
                                    On passe d'un terme au suivant en ajoutant toujours le même nombre r.
                                    Terme général : Un = U0 + n×r, ou bien Un = Up + (n - p)×r.
                                    Somme des n premiers termes :
                                    U0 + U1 + ... + Un = (n + 1) × (U0 + Un) / 2.
                                    Autrement dit : nombre de termes × (premier terme + dernier terme) / 2.

                                    SUITE GÉOMÉTRIQUE
                                    Une suite est géométrique de raison q (q non nul) si U(n+1) = Un × q.
                                    On passe d'un terme au suivant en multipliant toujours par le même nombre q.
                                    Terme général : Un = U0 × q^n, ou bien Un = Up × q^(n - p).
                                    Somme des n premiers termes, si q ≠ 1 :
                                    U0 + U1 + ... + Un = U0 × (1 - q^(n+1)) / (1 - q).

                                    SENS DE VARIATION
                                    Une suite arithmétique est croissante si r > 0, décroissante si r < 0, constante si r = 0.
                                    Une suite géométrique à termes positifs est croissante si q > 1 et décroissante si 0 < q < 1.
                                    TXT,
                            ],
                        ],
                    ],
                    [
                        'titre' => 'Trigonométrie',
                        'description' => "Cercle trigonométrique, angles remarquables et formules d'addition.",
                        'lecons' => [
                            [
                                'titre' => 'Cercle trigonométrique et formules',
                                'contenu' => <<<'TXT'
                                    Le cercle trigonométrique est le cercle de centre O et de rayon 1.
                                    À tout réel x on associe un point M du cercle ; on définit alors cos(x) comme
                                    l'abscisse de M et sin(x) comme l'ordonnée de M.

                                    RELATION FONDAMENTALE
                                    Pour tout réel x : cos²(x) + sin²(x) = 1.

                                    VALEURS REMARQUABLES
                                    - cos(0) = 1 et sin(0) = 0
                                    - cos(π/6) = √3/2 et sin(π/6) = 1/2
                                    - cos(π/4) = √2/2 et sin(π/4) = √2/2
                                    - cos(π/3) = 1/2 et sin(π/3) = √3/2
                                    - cos(π/2) = 0 et sin(π/2) = 1
                                    - cos(π) = -1 et sin(π) = 0

                                    ANGLES ASSOCIÉS
                                    - cos(-x) = cos(x) et sin(-x) = -sin(x) : le cosinus est pair, le sinus est impair.
                                    - cos(π - x) = -cos(x) et sin(π - x) = sin(x).
                                    - cos(π + x) = -cos(x) et sin(π + x) = -sin(x).

                                    FORMULES D'ADDITION
                                    - cos(a + b) = cos(a)cos(b) - sin(a)sin(b)
                                    - cos(a - b) = cos(a)cos(b) + sin(a)sin(b)
                                    - sin(a + b) = sin(a)cos(b) + cos(a)sin(b)
                                    - sin(a - b) = sin(a)cos(b) - cos(a)sin(b)

                                    FORMULES DE DUPLICATION
                                    - cos(2a) = cos²(a) - sin²(a) = 2cos²(a) - 1 = 1 - 2sin²(a)
                                    - sin(2a) = 2 sin(a) cos(a)

                                    La fonction cosinus et la fonction sinus sont périodiques de période 2π.
                                    TXT,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'nom' => '2ème Année Bac',
                'chapitres' => [
                    [
                        'titre' => 'Limites et continuité',
                        'description' => "Calculer des limites, lever les formes indéterminées et appliquer le théorème des valeurs intermédiaires.",
                        'lecons' => [
                            [
                                'titre' => 'Limites de fonctions',
                                'contenu' => <<<'TXT'
                                    La limite d'une fonction f en un point a décrit le comportement de f(x) lorsque x
                                    se rapproche de a sans nécessairement l'atteindre.

                                    LIMITES USUELLES EN +∞
                                    - lim x^n = +∞ pour tout entier n ≥ 1.
                                    - lim 1/x = 0.
                                    - lim √x = +∞.

                                    OPÉRATIONS
                                    La limite d'une somme, d'un produit ou d'un quotient se calcule terme à terme,
                                    sauf dans les cas de formes indéterminées :
                                    « ∞ - ∞ », « 0 × ∞ », « 0/0 » et « ∞/∞ ».

                                    LEVER UNE INDÉTERMINATION
                                    - Pour un polynôme en +∞ ou -∞, on factorise par le terme de plus haut degré.
                                      La limite d'un polynôme en l'infini est celle de son terme de plus haut degré.
                                    - Pour une fonction rationnelle en l'infini, la limite est celle du quotient des
                                      termes de plus haut degré du numérateur et du dénominateur.
                                    - Pour une forme 0/0 en un point a, on factorise par (x - a) au numérateur et au
                                      dénominateur, puis on simplifie.
                                    - Pour une expression avec des racines, on multiplie par la quantité conjuguée.

                                    CONTINUITÉ
                                    Une fonction f est continue en a si lim f(x) quand x tend vers a est égale à f(a).
                                    Les fonctions polynômes, rationnelles, racine et les fonctions trigonométriques sont
                                    continues sur tout intervalle de leur ensemble de définition.

                                    THÉORÈME DES VALEURS INTERMÉDIAIRES
                                    Si f est continue sur [a ; b] et si k est compris entre f(a) et f(b), alors il existe
                                    au moins un réel c dans [a ; b] tel que f(c) = k.
                                    Si de plus f est strictement monotone sur [a ; b], alors ce réel c est unique.
                                    En particulier, si f(a) et f(b) sont de signes contraires, l'équation f(x) = 0 admet
                                    au moins une solution dans l'intervalle [a ; b].
                                    TXT,
                            ],
                            [
                                'titre' => 'Dérivation et étude de fonctions',
                                'contenu' => <<<'TXT'
                                    Le nombre dérivé de f en a est la limite, quand h tend vers 0, du taux d'accroissement
                                    (f(a + h) - f(a)) / h. Géométriquement, f'(a) est le coefficient directeur de la
                                    tangente à la courbe de f au point d'abscisse a.

                                    Équation de la tangente au point d'abscisse a :
                                    y = f'(a) × (x - a) + f(a).

                                    DÉRIVÉES USUELLES
                                    - Si f(x) = k (constante), alors f'(x) = 0.
                                    - Si f(x) = x^n, alors f'(x) = n × x^(n-1).
                                    - Si f(x) = √x, alors f'(x) = 1 / (2√x).
                                    - Si f(x) = 1/x, alors f'(x) = -1/x².
                                    - Si f(x) = sin(x), alors f'(x) = cos(x).
                                    - Si f(x) = cos(x), alors f'(x) = -sin(x).

                                    OPÉRATIONS SUR LES DÉRIVÉES
                                    - (u + v)' = u' + v'
                                    - (u × v)' = u'v + uv'
                                    - (u / v)' = (u'v - uv') / v²
                                    - (u^n)' = n × u' × u^(n-1)

                                    VARIATIONS
                                    Le signe de la dérivée donne le sens de variation :
                                    - si f'(x) > 0 sur un intervalle, alors f est strictement croissante sur cet intervalle ;
                                    - si f'(x) < 0, alors f est strictement décroissante ;
                                    - si f' s'annule en changeant de signe en a, alors f admet un extremum local en a.

                                    Un maximum local correspond à un changement de signe de f' du positif vers le négatif,
                                    un minimum local à un changement du négatif vers le positif.
                                    TXT,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
