<?php

namespace App\Filament\Resources;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\ClassMajorResource\Pages;
use App\Filament\Resources\ClassMajorResource\RelationManagers;
use App\Models\ClassesMajor; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\ClassesMajorExporter;
class ClassMajorResource extends Resource
{
    use Translatable;
    protected static ?string $model = ClassesMajor::class; 

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                  Forms\Components\Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->label('Tổ chức')
                    ->required(),

             

Forms\Components\TextInput::make('name')
    ->label('Tên đơn vị')
    ->required(),



Forms\Components\Textarea::make('description')
    ->label('Mô tả'),


                Forms\Components\TextInput::make('code')
                    ->label('Mã đơn vị')
                    ->required(),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->label('Đơn vị Cha')
                    ->searchable(),

                Forms\Components\TagsInput::make('tags')
                    ->label('Tags'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên đơn vị')
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Mã'),
  
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Đơn vị Cha'),

                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Tổ chức'),
            ])
            ->filters([
              SelectFilter::make('parent_id')
    ->label('Lọc theo loại')
    ->options(fn () => ClassesMajor::query()
        ->select('id','name')
        ->pluck('name', 'id'))

    ->query(function (Builder $query, array $data): Builder {
    if (! empty($data['value'])) {
        $query->whereRaw("parent_id = ?", [$data['value']]);
    }
    return $query;
})


            ]) 
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                ]),
            ])
             ->headerActions([
            ExportAction::make()
                ->exporter(ClassesMajorExporter::class)
        ]);;
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
            'index' => Pages\ListClassMajors::route('/'),
            'create' => Pages\CreateClassMajor::route('/create'),
            'edit' => Pages\EditClassMajor::route('/{record}/edit'),
            'view' => Pages\ViewClassMajor::route('/{record}'),
        ];
    }
}
