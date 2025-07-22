<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification; // <-- THÊM DÒNG NÀY
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'choices';

    // Sử dụng getLabel và getPluralLabel để gọi file ngôn ngữ
    public static function getLabel(): string
    {
        return __('choices-relation-manager.labels.singular');
    }

    public static function getPluralLabel(): string
    {
        return __('choices-relation-manager.labels.plural');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tabs cho việc nhập liệu đa ngôn ngữ (VI & EN)
                Forms\Components\Tabs::make('Translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('choices-relation-manager.form.tabs.vi'))
                            ->schema([
                                Forms\Components\Textarea::make('choice_text.vi')
                                    ->label(false) // Ẩn label vì đã có ở tiêu đề Tab
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make(__('choices-relation-manager.form.tabs.en'))
                            ->schema([
                                Forms\Components\Textarea::make('choice_text.en')
                                    ->label(false), // Ẩn label vì đã có ở tiêu đề Tab
                            ]),
                    ])->columnSpanFull(),

                // Toggle để xác định đáp án đúng
                Forms\Components\Toggle::make('is_correct')
                    ->label(__('choices-relation-manager.form.fields.is_correct')),

                // Trường nhập thứ tự với đầy đủ validation
                Forms\Components\TextInput::make('choice_order')
                    ->label(__('choices-relation-manager.form.fields.choice_order'))
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->minValue(0) // Quy tắc 1: Đảm bảo giá trị không được nhỏ hơn 0
                    ->rule(function (RelationManager $livewire, ?Model $record) {
                        // Quy tắc 2: Đảm bảo giá trị là duy nhất trong phạm vi câu hỏi hiện tại
                        return function (string $attribute, $value, \Closure $fail) use ($livewire, $record) {
                            // Lấy model Question cha (owner record) của relation manager này
                            $query = $livewire->getOwnerRecord()
                                ->choices()
                                ->where('choice_order', $value);

                            // Nếu đang ở form chỉnh sửa, phải loại trừ bản ghi hiện tại ra khỏi việc kiểm tra
                            if ($record) {
                                $query->where('id', '!=', $record->id);
                            }

                            // Nếu tìm thấy một bản ghi khác có cùng thứ tự, báo lỗi
                            if ($query->exists()) {
                                $fail(__('choices-relation-manager.form.validation.unique_order'));
                            }
                        };
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('choice_text')
            ->columns([
                // Cột hiển thị nội dung, tự động theo ngôn ngữ hiện tại của người dùng
                Tables\Columns\TextColumn::make('choice_text')
                    ->label(__('choices-relation-manager.table.columns.choice_text'))
                    ->limit(60)
                    ->wrap()
                    ->getStateUsing(fn ($record): ?string => $record->getTranslation('choice_text', app()->getLocale())),

                // Cột hiển thị icon cho đáp án đúng/sai
                Tables\Columns\IconColumn::make('is_correct')
                    ->label(__('choices-relation-manager.table.columns.is_correct'))
                    ->boolean(),

                // Cột hiển thị thứ tự, có thể sắp xếp
                Tables\Columns\TextColumn::make('choice_order')
                    ->label(__('choices-relation-manager.table.columns.choice_order'))
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('choices-relation-manager.table.actions.create'))
                    // THÊM MỚI: Thông báo khi tạo thành công
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('choices-relation-manager.notifications.created.title'))
                            ->body(__('choices-relation-manager.notifications.created.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    // THÊM MỚI: Thông báo khi sửa thành công
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('choices-relation-manager.notifications.updated.title'))
                            ->body(__('choices-relation-manager.notifications.updated.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    // THÊM MỚI: Thông báo khi xóa thành công
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('choices-relation-manager.notifications.deleted.title'))
                            ->body(__('choices-relation-manager.notifications.deleted.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
