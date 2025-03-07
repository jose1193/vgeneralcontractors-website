<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // Añade la página principal
        $sitemap->add(
            Url::create(config('app.url'))
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // Añade otras páginas importantes
        $urls = [
            '/' => [
                'priority' => 1.0,
                'frequency' => Url::CHANGE_FREQUENCY_DAILY
            ],
            '/about' => [
                'priority' => 0.8,
                'frequency' => Url::CHANGE_FREQUENCY_MONTHLY
            ],
            '/services' => [
                'priority' => 0.9,
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY
            ],
            '/projects' => [
                'priority' => 0.9,
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY
            ],
            '/contact' => [
                'priority' => 0.7,
                'frequency' => Url::CHANGE_FREQUENCY_MONTHLY
            ],
            '/blog' => [
                'priority' => 0.8,
                'frequency' => Url::CHANGE_FREQUENCY_DAILY
            ],
            '/testimonials' => [
                'priority' => 0.7,
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY
            ],
            '/gallery' => [
                'priority' => 0.8,
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY
            ],
            '/careers' => [
                'priority' => 0.6,
                'frequency' => Url::CHANGE_FREQUENCY_WEEKLY
            ]
        ];

        foreach ($urls as $url => $settings) {
            $sitemap->add(
                Url::create(config('app.url') . $url)
                    ->setLastModificationDate(Carbon::yesterday())
                    ->setChangeFrequency($settings['frequency'])
                    ->setPriority($settings['priority'])
            );
        }

        // Añadir páginas dinámicas si existen (ejemplo: proyectos, posts, etc.)
        // Aquí puedes agregar lógica para obtener URLs dinámicas de tu base de datos
        
        // Guarda el sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
} 