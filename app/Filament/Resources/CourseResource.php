<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Organization;
use App\States\Status;
class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Môn học';
     public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Bố cục 2 cột: cột chính chiếm 2/3, cột phụ 1/3
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Thông tin chung')
                            ->schema([
                                // Tên (đa ngôn ngữ) - Filament tự động nhận diện
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên môn học')
                                    ->required()
                                    ->translatable(), // Kích hoạt đa ngôn ngữ

                                // Mô tả (đa ngôn ngữ)
                                Forms\Components\RichEditor::make('description')
                                    ->label('Mô tả chi tiết')
                                    ->translatable()
                                    ->columnSpanFull(),

                                // Gắn thẻ (spatie/laravel-tags)
                                Forms\Components\SpatieTagsInput::make('tags')
                                    ->label('Gắn thẻ/Phân loại'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Cột phụ bên phải
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Trạng thái & Cấu hình')
                            ->schema([
                                // Ảnh đại diện (spatie/laravel-medialibrary)
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->label('Ảnh đại diện')
                                    ->collection('image') // Tên collection đã đăng ký trong model
                                    ->image(),

                                // Mã môn học
                                Forms\Components\TextInput::make('code')
                                    ->label('Mã môn học')
                                    ->required()
                                    ->unique(ignoreRecord: true), // Không kiểm tra unique trên bản ghi hiện tại

                                // Trạng thái (spatie/laravel-model-states)
                                Forms\Components\Select::make('status')
                                    ->label('Trạng thái')
                                    ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state => $state::getLabel()]))
                                    ->required(),

                                // Môn học cha (parent_id)
                                Forms\Components\Select::make('parent_id')
                                    ->label('Thuộc môn học cha')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    // Loại bỏ môn học hiện tại khỏi danh sách chọn
                                    ->options(fn (?Course $record) => Course::where('id', '!=', $record?->id)->pluck('name', 'id')),

                                // Tổ chức (organization_id)
                                Forms\Components\Select::make('organization_id')
                                    ->label('Tổ chức')
                                    ->relationship('organization', 'name')
                                    ->searchable()
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Thời gian')
                            ->schema([
                                // Ngày bắt đầu
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Ngày bắt đầu'),

                                // Ngày kết thúc
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Ngày kết thúc')
                                    ->afterOrEqual('start_date'), // Phải sau hoặc bằng ngày bắt đầu
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            // Cột ảnh đại diện
            Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                ->label('Ảnh')
                ->collection('image')
                ->circular(),

            // Cột Tên môn học
            Tables\Columns\TextColumn::make('name')
                ->label('Tên môn học')
                ->searchable()
                ->sortable(),

            // Cột Mã môn học
            Tables\Columns\TextColumn::make('code')
                ->label('Mã')
                ->searchable(),

            // Cột Trạng thái (hiển thị dạng badge)
            Tables\Columns\TextColumn::make('status')
                ->label('Trạng thái')
                ->badge()
                ->formatStateUsing(fn ($state) => $state::getLabel()) // Lấy label từ class State
                ->color(fn ($state): string => match(true) {
                    $state instanceof \App\States\Course\Active => 'success',
                    $state instanceof \App\States\Course\Inactive => 'warning',
                    $state instanceof \App\States\Course\Archived => 'gray',
                    default => 'info',
                }),

            // Cột Thẻ (tags)
            Tables\Columns\SpatieTagsColumn::make('tags')
                ->label('Thẻ'),

            // Cột Tổ chức
            Tables\Columns\TextColumn::make('organization.name')
                ->label('Tổ chức')
                ->sortable(),

            // Cột Ngày cập nhật
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Cập nhật lúc')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Mặc định ẩn
        ])
        ->filters([
            // Bộ lọc theo trạng thái
            Tables\Filters\SelectFilter::make('status')
                ->label('Lọc theo trạng thái')
                ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state => $state::getLabel()])),
            
            // Bộ lọc theo tổ chức
            Tables\Filters\SelectFilter::make('organization_id')
                ->label('Lọc theo tổ chức')
                ->relationship('organization', 'name')
                ->searchable(),
                
            Tables\Filters\TrashedFilter::make(), // Bộ lọc cho SoftDeletes
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
