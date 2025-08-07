<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = null; // Không sử dụng model để tránh thay đổi database

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Tài liệu';

    protected static ?string $modelLabel = 'Tài liệu';

    protected static ?string $pluralModelLabel = 'Tài liệu';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->rows(3),
                Forms\Components\FileUpload::make('file_path')
                    ->label('Tệp tài liệu')
                    ->directory('documents')
                    ->acceptedFileTypes(['pdf', 'doc', 'docx', 'txt'])
                    ->required(),
                Forms\Components\Toggle::make('is_public')
                    ->label('Công khai')
                    ->default(false),
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    // Override để trả về query builder hợp lệ
    public static function getEloquentQuery(): Builder
    {
        // Sử dụng User model làm base query để tránh lỗi
        return \App\Models\User::query()->whereRaw('1 = 0'); // Query rỗng
    }

    // Override để xử lý table data mà không cần model
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Chưa có tài liệu nào')
            ->emptyStateDescription('Tính năng này đang được phát triển.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
