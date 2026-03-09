<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Implementasi Tabs Menjadi Vertical sesuai Gambar F & G
                Tabs::make('Product Tabs')
                    ->tabs([
                        // Tab 1 dengan Icon (Latihan J Poin 4)
                        Tab::make('Product Details')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('id')
                                    ->label('Product ID'),
                                TextEntry::make('sku')
                                    ->label('Product SKU')
                                    ->badge() // Latihan K Poin 1
                                    ->color('success'),
                                TextEntry::make('description')
                                    ->label('Product Description'),
                                TextEntry::make('created_at')
                                    ->label('Product Creation Date')
                                    ->date('d M Y')
                                    ->color('info'),
                            ]),
                        
                        // Tab 2 dengan Icon & Badge Dinamis (Latihan J Poin 1 & 4)
                        Tab::make('Product Price and Stock')
                            ->icon('heroicon-m-shopping-cart')
                            ->badge(fn ($record) => $record->stock > 0 ? $record->stock : 'Out of Stock')
                            ->badgeColor(fn ($record) => $record->stock > 10 ? 'success' : 'danger')
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Product Price')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->icon('heroicon-s-currency-dollar')
                                    // Latihan K Poin 3: Format Rupiah
                                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, ',', '.')),
                                TextEntry::make('stock')
                                    ->label('Product Stock')
                                    ->weight('bold')
                                    ->color('primary')
                                    ->icon('heroicon-o-archive-box'), // Latihan K Poin 2
                            ]),

                        // Tab 3 dengan Icon (Latihan J Poin 4)
                        Tab::make('Image and Status')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Product Image')
                                    ->disk('public'),
                                IconEntry::make('is_active')
                                    ->label('Is Active?')
                                    ->boolean(),
                                IconEntry::make('is_featured')
                                    ->label('Is Featured?')
                                    ->boolean(),
                            ]),
                    ])
                    ->vertical() // Mengubah Tabs menjadi Vertical (Gambar F & G)
                    ->columnSpanFull(),

                // Section tambahan sesuai Gambar K Poin 5
                Section::make('Product Info')
                    ->schema([
                        TextEntry::make('name')->label('Product Name'),
                        TextEntry::make('sku')->label('Product SKU')->badge()->color('success'),
                    ])->columnSpanFull()->collapsed(),
            ]);
    }
}