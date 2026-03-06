<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox; 
use Filament\Forms\components\DateTimePicker;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            TextInput::make('title')->required(),
            TextInput::make('slug')->required(),
            Select::make('category_id')
            ->label('Category')
            ->options(
            \App\Models\Category::all()->pluck('name', 'id')
            )
            ->required(),
            ColorPicker::make('color'),
            RichEditor::make('body'),
            FileUpload::make('image')->image()->disk('public')->directory('posts'),
            TagsInput::make('tags'),
            Checkbox::make('published'),
            DateTimePicker::make('published_at'),
            
            ]);
    }
}
