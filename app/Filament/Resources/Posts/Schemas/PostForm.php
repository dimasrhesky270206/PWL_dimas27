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
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;

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
                            // Validasi Title: Minimal 5 karakter & Wajib diisi
                            TextInput::make("title")
                                ->required()
                                ->minLength(5)
                                ->maxLength(50)
                                ->validationMessages([
                                    'minLength' => 'Judul terlalu pendek, minimal harus 5 karakter.',
                                    'required' => 'Judul postingan tidak boleh dikosongkan.',
                                ]),
                            
                            // Validasi Slug: Unik, Wajib diisi & Minimal 3 karakter
                            TextInput::make("slug")
                                ->required()
                                ->minLength(3)
                                ->unique(ignoreRecord: true)
                                ->validationMessages([ 
                                    'unique' => 'Slug sudah digunakan oleh postingan lain.',
                                    'minLength' => 'Slug minimal terdiri dari 3 karakter.',
                                ]),

                            // Validasi Category: Wajib dipilih
                            Select::make("category_id")
                                ->relationship("category", "name")
                                ->required() // Menambahkan required sesuai instruksi
                                ->preload()
                                ->searchable(),

                            ColorPicker::make('color'),
                        ])->columns(2), 
                    
                    MarkdownEditor::make("content")
                        ->columnSpanFull(),
                ])->columnSpan(2),

                Group::make([
                    Section::make("Image Upload")
                        ->schema([
                            // Validasi Image: Wajib diupload
                            FileUpload::make("image")
                                ->required() // Menambahkan required sesuai instruksi
                                ->image()
                                ->disk("public")
                                ->directory("posts")
                                ->validationMessages([
                                    'required' => 'Mohon unggah gambar sampul untuk postingan ini.',
                                ]),
                        ]),

                    Section::make("Meta Information")
                        ->schema([
                            TagsInput::make("tags"),
                            Checkbox::make("published"),
                            DateTimePicker::make("published_at"),
                        ]),
                ])->columnSpan(1),
            ]) 
            ->columns(3); 
    }
}