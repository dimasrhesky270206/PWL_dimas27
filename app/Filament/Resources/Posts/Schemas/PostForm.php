<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\MarkdownEditor;   
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Checkbox; 
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use App\Models\Category;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make("Post Details")
                        ->description("Fill in the details of the post")
                        ->icon('heroicon-o-document-text')
                        ->schema([ 
                            // Validasi Title sesuai foto: min:3 | max:10 (Contoh di foto)
                            // Catatan: Saya sesuaikan dengan rules di gambar Anda (baris 36)
                            TextInput::make("title")
                                ->rules(['required', 'min:3', 'max:10']),
                            
                            // Validasi Slug: unique & validationMessages (Baris 37-42 di foto)
                            TextInput::make("slug")
                                ->rules(['required'])
                                ->unique(ignoreRecord: true)
                                ->validationMessages([ 
                                    'unique' => 'Slug must be unique',
                                ]),

                            // PERBAIKAN UTAMA: Select Category (Baris 43-48 di foto)
                            Select::make("category_id")
                                ->relationship("category", "name")
                                ->options(Category::all()->pluck("name", "id"))
                                ->required()
                                ->searchable(),

                            ColorPicker::make('color'),
                        ])->columns(2), 
                    
                    MarkdownEditor::make("content")
                        ->columnSpanFull(),
                ])->columnSpan(2),

                Group::make([
                    Section::make("Image Upload")
                        ->schema([
                            FileUpload::make("image")
                                ->required()
                                ->image()
                                ->disk("public")
                                ->directory("posts")
                                ->validationMessages([
                                    'required' => 'Mohon unggah gambar sampul untuk postingan ini.',
                                ]),
                        ]),

                    Section::make("Meta Information")
                        ->schema([
                            Select::make('tags')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->preload(),
                            Checkbox::make("published"),
                            DateTimePicker::make("published_at"),
                        ]),
                ])->columnSpan(1),
            ]) 
            ->columns(3); 
    }
}