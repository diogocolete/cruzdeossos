<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class IconPickerModal extends Field
{
    protected string $view = 'filament.forms.components.icon-picker-modal';

    protected array $icons = [];

    private function loadIcons(): array
    {
        $factory = app(\BladeUI\Icons\Factory::class);
        $icons = [];

        foreach ($factory->all() as $set) {
            $prefix = $set['prefix'];
            foreach ($set['paths'] as $path) {
                if (!is_dir($path)) continue;
                foreach (\Illuminate\Support\Facades\File::allFiles($path) as $file) {
                    if ($file->getExtension() !== 'svg') continue;
                    $name = str($file->getPathname())
                        ->after($path . DIRECTORY_SEPARATOR)
                        ->replace(DIRECTORY_SEPARATOR, '.')
                        ->basename('.svg')
                        ->toString();
                    $icons[] = "$prefix-$name";
                }
            }
        }
        sort($icons);
        return $icons;
    }

    public function getIcons(): array
    {
        if (empty($this->icons)) {
            $this->icons = $this->loadIcons();
        }

        return $this->icons;
    }
}
