<?php

namespace App\Http\Controllers;

use App\Services\ContentRepository;
use App\Services\ProductCatalog;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function __construct(
        protected ContentRepository $content,
        protected ProductCatalog $catalog,
    ) {}

    public function home()
    {
        return view('pages.home', [
            'recipes' => array_slice($this->content->recipes(), 0, 3),
            'allRecipes' => $this->content->recipes(),
            'districts' => array_values($this->content->districts()),
            'products' => array_slice($this->catalog->all(), 0, 4),
            'stories' => array_values($this->content->stories()),
        ]);
    }

    public function recipes()
    {
        $recipes = array_values($this->content->recipes());

        // Data ringan untuk pencarian & filter di sisi browser.
        $filterIndex = array_map(fn (array $r) => [
            'slug' => $r['slug'],
            'category' => $r['category'],
            'spicy' => $r['flavor_profile']['spicy'],
            'savory' => $r['flavor_profile']['savory'],
            'haystack' => mb_strtolower(implode(' ', [
                $r['name'], $r['region'], $r['short_desc'], $r['category'],
                implode(' ', $r['main_ingredients']),
                implode(' ', $r['spice_blend']),
            ])),
        ], $recipes);

        return view('pages.recipes', [
            'recipes' => $recipes,
            'filterIndex' => $filterIndex,
            'categories' => array_values(array_unique(array_column($recipes, 'category'))),
        ]);
    }

    public function recipe(string $slug)
    {
        $recipe = $this->content->recipe($slug);
        abort_if($recipe === null, 404);

        $others = array_values(array_filter(
            $this->content->recipes(),
            fn (array $r) => $r['slug'] !== $slug
        ));

        return view('pages.recipe', [
            'recipe' => $recipe,
            'related' => array_slice($others, 0, 3),
        ]);
    }

    public function regions()
    {
        return view('pages.regions', [
            'districts' => array_values($this->content->districts()),
            'recipes' => $this->content->recipes(),
        ]);
    }

    public function market()
    {
        return view('pages.market', [
            'products' => $this->catalog->all(),
        ]);
    }

    public function stories()
    {
        return view('pages.stories', [
            'stories' => array_values($this->content->stories()),
        ]);
    }

    public function story(string $slug)
    {
        $story = $this->content->story($slug);
        abort_if($story === null, 404);

        $others = array_values(array_filter(
            $this->content->stories(),
            fn (array $s) => $s['slug'] !== $slug
        ));

        return view('pages.story', [
            'story' => $story,
            'others' => $others,
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sitemap(): Response
    {
        $urls = [
            route('home'),
            route('recipes.index'),
            route('regions'),
            route('market'),
            route('stories.index'),
            route('contact'),
        ];

        foreach (array_keys($this->content->recipes()) as $slug) {
            $urls[] = route('recipes.show', $slug);
        }

        foreach (array_keys($this->content->stories()) as $slug) {
            $urls[] = route('stories.show', $slug);
        }

        $lastmod = now()->toDateString();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url).'</loc><lastmod>'.$lastmod.'</lastmod></url>'."\n";
        }

        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
