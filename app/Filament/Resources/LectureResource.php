<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LectureResource\Pages;
use App\Models\Lecture;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists; // MỚI: Import Infolist
use Filament\Infolists\Infolist; // MỚI: Import Infolist
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $modelLabel = 'Bài giảng';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->columns(3)->schema([
                    Forms\Components\Group::make()->columnSpan(2)->schema([
                        Forms\Components\Section::make('Nội dung bài giảng')
                            ->schema([
                                Forms\Components\TextInput::make('title')->required()->maxLength(255)->label('Tiêu đề bài giảng'),
                                Forms\Components\RichEditor::make('description')->columnSpanFull()->label('Mô tả'),
                            ]),
                    ]),
                    Forms\Components\Group::make()->columnSpan(1)->schema([
                        Forms\Components\Section::make('Thông tin chung')
                            ->schema([
                                Forms\Components\Select::make('course_id')->relationship('course', 'name')->searchable()->preload()->required()->label('Khóa học'),
                                Forms\Components\TextInput::make('duration_estimate')
                                    ->label('Thời lượng dự kiến')
                                    ->mask('99:99')->placeholder('00:00')
                                    ->rules(['regex:/^([0-3][0-9]|4[0-8]):[0-5][0-9]$/'])
                                    ->extraInputAttributes([
                                        'x-data' => '','@blur' => '
                                            let el = $event.target;
                                            let val = el.value;
                                            if (val.match(/^\d{2}:$/)) {
                                                el.value = val + \'00\';
                                                $dispatch(\'input\', el.value);
                                            }
                                        ',
                                    ]),
                                Forms\Components\TextInput::make('lecture_order')->required()->numeric()->default(0)->label('Thứ tự bài giảng'),
                                Forms\Components\Select::make('status')->options(['active' => 'Hoạt động','inactive' => 'Không hoạt động'])->required()->default('active')->label('Trạng thái'),
                            ]),
                    ]),
                ]),
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make()->columns(3)->schema([
                    Infolists\Components\Group::make()->columnSpan(2)->schema([
                        Infolists\Components\Section::make('Nội dung bài giảng')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')->label('Tiêu đề'),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('Mô tả')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                    Infolists\Components\Group::make()->columnSpan(1)->schema([
                        Infolists\Components\Section::make('Thông tin chung')
                            ->schema([
                                Infolists\Components\TextEntry::make('course.name')->label('Thuộc khóa học :'),
                                Infolists\Components\TextEntry::make('duration_estimate')
                                    ->label('Thời lượng dự kiến :')
                                    ->formatStateUsing(function (?string $state): string {
                                        if (empty($state)) return '-';
                                        $parts = explode(':', $state);
                                        if (count($parts) !== 2) return $state;
                                        $hours = (int) $parts[0];
                                        $minutes = (int) $parts[1];
                                        $displayParts = [];
                                        if ($hours > 0) $displayParts[] = "{$hours} hours";
                                        if ($minutes > 0) $displayParts[] = "{$minutes} minutes";
                                        return count($displayParts) > 0 ? implode(' ', $displayParts) : '0 phút';
                                    }),
                                Infolists\Components\TextEntry::make('lecture_order')->label('Thứ tự bài giảng :')->numeric()->default(0),
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Trạng thái :')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        default => 'gray',
                                    })->formatStateUsing(fn(string $state): string => match ($state) {
                                        'active' => 'Hoạt động',
                                        'inactive' => 'Không hoạt động',
                                        default => $state,
                                    }),
                            ]),
                    ]),
                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('lecture_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Tiêu đề bài giảng')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('course.name')->label('Khóa học')->searchable()->sortable()->limit(30),
                Tables\Columns\TextColumn::make('duration_estimate')
                    ->label('Thời lượng dự kiến')
                    ->formatStateUsing(function (?string $state): string {
                        if (empty($state)) return '-';
                        $parts = explode(':', $state);
                        if (count($parts) !== 2) return $state;
                        $hours = (int) $parts[0];
                        $minutes = (int) $parts[1];
                        $displayParts = [];
                        if ($hours > 0) $displayParts[] = "{$hours} hours";
                        if ($minutes > 0) $displayParts[] = "{$minutes} minutes";
                        return count($displayParts) > 0 ? implode(' ', $displayParts) : '0 phút';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lecture_order')->label('#')->toggleable(isToggledHiddenByDefault: true)->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Trạng thái')->badge()->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Ngày tạo')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')->relationship('course', 'name')->label('Lọc theo khóa học'),
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Hoạt động', 'inactive' => 'Không hoạt động'])->label('Lọc theo trạng thái'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(), 
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLectures::route('/'),
            'view' => Pages\ViewLecture::route('/{record}'),
            'create' => Pages\CreateLecture::route('/create'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}