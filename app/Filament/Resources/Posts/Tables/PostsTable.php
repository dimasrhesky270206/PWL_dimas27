<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Judul dengan fitur sortable
                TextColumn::make('title')
                    ->sortable(), 

                // Kolom Slug dengan fitur sortable
                TextColumn::make('slug')
                    ->sortable(),

                // Kolom Kategori dengan fitur sortable
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                // Kolom Warna
                ColorColumn::make('color'),

                // Kolom Gambar dengan disk public
                ImageColumn::make('image')
                    ->disk('public'),

                // Penambahan Kolom Created At sesuai Gambar 9f8cfd.png
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            // Menambahkan pengurutan default sesuai Gambar 9f857c.png
            ->defaultSort('created_at', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}   