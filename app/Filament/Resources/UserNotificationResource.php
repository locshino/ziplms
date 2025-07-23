<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserNotificationResource\Pages;
use App\Jobs\SendBulkNotificationEmail;
use App\Models\States\Notification\Read;
use App\Models\States\Notification\Unread;
use App\Models\User;
use App\Models\UserNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification; // Đảm bảo import đúng Notification Facade
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserNotificationResource extends Resource
{
    protected static ?string $model = UserNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Giao tiếp & Hỗ trợ';

    protected static ?string $navigationLabel = 'Thông báo Người dùng';

    protected static ?string $pluralModelLabel = 'Thông báo Người dùng';

    protected static ?string $modelLabel = 'Thông báo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Người dùng')
                            ->disabled(), // Giữ trường này vô hiệu hóa vì người nhận không nên thay đổi
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required(), // Bỏ disabled để có thể sửa
                        Forms\Components\Textarea::make('body')
                            ->label('Nội dung')
                            ->columnSpanFull()
                            ->required(), // Bỏ disabled để có thể sửa
                        Forms\Components\KeyValue::make('data')
                            ->label('Dữ liệu bổ sung')
                            ->disabled(), // Giữ trường này vô hiệu hóa
                        Forms\Components\Placeholder::make('status')
                            ->label('Trạng thái')
                            ->content(fn (?UserNotification $record): string => $record ? $record->status->label() : '-'),
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Ngày tạo')
                            ->content(fn (?UserNotification $record): string => $record ? $record->created_at->format('d/m/Y H:i') : '-'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // Action để gửi thông báo hàng loạt
                Action::make('sendBulkNotification')
                    ->label('Gửi thông báo hàng loạt')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('recipients')
                            ->label('Người nhận')
                            ->helperText('Bạn có thể tìm kiếm người dùng theo tên hoặc email.')
                            ->multiple()
                            ->searchable()
                            ->getSearchResultsUsing(
                                fn (string $search) => User::where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                            )
                            ->getOptionLabelsUsing(
                                fn (array $values): array => User::whereIn('id', $values)->pluck('name', 'id')->toArray()
                            )
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required(),
                        Forms\Components\Textarea::make('body')
                            ->label('Nội dung')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('send_email')
                            ->label('Gửi email cho người nhận')
                            ->helperText('Nếu bật, email sẽ được gửi đến địa chỉ email của người dùng.'),
                    ])
                    ->action(function (array $data) {
                        $users = User::find($data['recipients']);
                        $sentEmailCount = 0;

                        foreach ($users as $user) {
                            // (Tùy chọn) Tạo bản ghi trong resource của bạn để lưu lịch sử
                            UserNotification::create([
                                'user_id' => $user->id,
                                'title' => $data['title'],
                                'body' => $data['body'],
                                'status' => Unread::class, // Mặc định trạng thái là chưa đọc
                            ]);

                            // *** GỬI THÔNG BÁO REAL-TIME ĐẾN CHUÔNG CỦA NGƯỜI DÙNG ***
                            Notification::make()
                                ->title($data['title'])
                                ->body($data['body'])
                                ->success()
                                ->sendToDatabase($user); // Gửi đến người dùng cụ thể

                            // Kiểm tra nếu tùy chọn gửi email được bật
                            if (isset($data['send_email']) && $data['send_email'] && $user->email) {
                                SendBulkNotificationEmail::dispatch($user, $data['title'], $data['body']);
                                $sentEmailCount++;
                            }
                        }

                        // Gửi thông báo thành công cho người quản trị đang thực hiện hành động
                        Notification::make()
                            ->title('Đã bắt đầu gửi thông báo')
                            ->body('Đã tạo '.count($data['recipients']).' thông báo. '.($data['send_email'] ? 'Hệ thống sẽ gửi '.$sentEmailCount.' email trong nền.' : ''))
                            ->success()
                            ->send();
                    })
                    ->modalWidth('2xl'),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (UserNotification $record): string => $record->title),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->color()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Cập nhật lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'read' => 'Đã đọc',
                        'unread' => 'Chưa đọc',
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query->when($data['value'], fn ($q) => $q->whereState('status', $data['value'] === 'read' ? Read::class : Unread::class))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
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
            'index' => Pages\ListUserNotifications::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        // Vô hiệu hóa việc tạo thông báo riêng lẻ từ form, chỉ cho phép gửi hàng loạt
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
