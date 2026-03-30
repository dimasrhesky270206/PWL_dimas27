<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction; 
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Checkbox; 
use Filament\Actions\Action;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('slug')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                ColorColumn::make('color'),

                ImageColumn::make('image')
                    ->disk('public'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('tags')
                    ->label('Tags')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(fn ($record) => $record->tags->pluck('name')->join(', ')),

                IconColumn::make('published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date'),
                    ])
                    ->query(function ($query, $data) {
                        return $query->when(
                            $data['created_at'],
                            fn ($query, $date) =>
                                $query->whereDate('created_at', $date)
                        );
                    }),

                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),
            ])
            ->recordActions([
                // 1. Replicate dengan Ikon
                ReplicateAction::make()
                    ->icon('heroicon-m-document-duplicate')
                    ->color('warning'),

                // 2. Edit dengan Ikon
                EditAction::make()
                    ->icon('heroicon-m-pencil-square'),

                // 3. Delete dengan Ikon
                DeleteAction::make()
                    ->icon('heroicon-m-trash'),

            
                Action::make('status')
                    ->label('status change')
                    ->icon('heroicon-m-arrow-path') 
                    ->color('info')
                    ->requiresConfirmation() 
                    ->modalHeading('Ubah Status Publikasi')
                    ->modalDescription('Apakah Anda yakin ingin mengubah status publikasi postingan ini?')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->schema([
                        Checkbox::make('published')
                            ->label('Terbitkan Postingan')
                            ->default(fn ($record): bool => (bool) $record->published),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'published' => $data['published']
                        ]);
                    }),
            ])
            ->bulkActions([ 
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}