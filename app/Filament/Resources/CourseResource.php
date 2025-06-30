<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\States\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table; // <--- 1. THÊM DÒNG NÀY

class CourseResource extends Resource
{
    use Translatable; // <--- 2. THÊM DÒNG NÀY

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Môn học';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Thông tin chung')
                            ->schema([
                                // Tên (đa ngôn ngữ)
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên môn học')
                                    ->required(),
                                // ->translatable(), // <-- Bỏ đi, không cần thiết

                                // Mô tả (đa ngôn ngữ)
                                Forms\Components\RichEditor::make('description')
                                    ->label('Mô tả chi tiết')
                                    // ->translatable() // <-- Bỏ đi, không cần thiết
                                    ->columnSpanFull(),

                                // Gắn thẻ
                                Forms\Components\SpatieTagsInput::make('tags')
                                    ->label('Phân loại'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                // Cột phụ bên phải
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Trạng thái & Cấu hình')
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                                    ->label('Ảnh đại diện')
                                    ->collection('image')
                                    ->disk('public') // Chỉ định rõ disk lưu trữ (dù là mặc định)
                                    ->image() // Bật chế độ xem trước và chỉnh sửa ảnh
                                    ->live() // Tải lên ngay lập tức, rất quan trọng cho form đa ngôn ngữ
                                    ->reorderable() // Giúp ổn định trạng thái trong một số trường hợp phức tạp
                                    ->maxSize(5120) // Tăng giới hạn kích thước tệp lên 5MB (5120 KB)
                                    ->validationMessages([
                                        'max' => 'Dung lượng ảnh không được vượt quá :max KB.',
                                        'image' => 'Tệp tải lên phải là hình ảnh.',
                                    ]),
                                Forms\Components\TextInput::make('code')
                                    ->label('Mã môn học')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('status')
                                    ->label('Trạng thái')
                                    ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state => $state::label()]))
                                    ->required(),
                                Forms\Components\Select::make('parent_id')
                                    ->label('Thuộc môn học cha')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->options(fn (?Course $record) => Course::where('id', '!=', $record?->id)->pluck('name', 'id')),
                                Forms\Components\Select::make('organization_id')
                                    ->label('Tổ chức')
                                    ->relationship('organization', 'name')
                                    ->searchable()
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Thời gian')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Ngày bắt đầu'),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Ngày kết thúc')
                                    ->afterOrEqual('start_date'),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->label('Ảnh')
                    ->collection('image')
                    ->circular(),
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
                    ->formatStateUsing(fn ($state) => $state::label()) // Lấy label từ class State
                    ->color(fn ($state): string => match (true) {
                        $state instanceof \App\States\Active => 'success',
                        $state instanceof \App\States\Inactive => 'warning',

                        default => 'info',
                    }),

                // Cột Thẻ (tags)
                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label('Phân Loại'),

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
                    ->options(collect(Status::getStates())->mapWithKeys(fn ($state) => [$state => $state::label()])),

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
