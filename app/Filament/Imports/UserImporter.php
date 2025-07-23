<?php

namespace App\Filament\Imports;

use App\Models\ClassesMajor;
use App\Models\Organization;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Mã người dùng')
                ->rules(['nullable', 'max:255', 'unique:users,code']),
            ImportColumn::make('name')
                ->label('Họ và tên')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('password')
                ->label('Mật khẩu')
                ->rules(['nullable', 'min:8']),
            ImportColumn::make('phone_number')
                ->label('Số điện thoại')
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('address')
                ->label('Địa chỉ')
                ->rules(['nullable']),
            ImportColumn::make('organizations')
                ->label('Các cơ sở')
                ->fillRecordUsing(null),
            ImportColumn::make('classes_majors')
                ->label('Các lớp/chuyên ngành')
                ->fillRecordUsing(null),
        ];
    }

    public function resolveRecord(): ?User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function afterFill(): void
    {
        if (! empty($this->data['password'])) {
            $this->getRecord()->password = Hash::make($this->data['password']);
        }
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();

        if (isset($this->options['role'])) {
            $role = Role::where('name', $this->options['role'])->first();
            if ($role) {
                $record->syncRoles([$role]);
            }
        }

        if (! empty($this->data['organizations'])) {
            $organizationNames = array_map('trim', explode(',', $this->data['organizations']));
            $organizationIds = Organization::whereIn('name', $organizationNames)->pluck('id');
            $record->organizations()->sync($organizationIds);
        }

        if (! empty($this->data['classes_majors'])) {
            $classNames = array_map('trim', explode(',', $this->data['classes_majors']));
            $classIds = ClassesMajor::whereIn('name', $classNames)->pluck('id');
            $record->classesMajors()->sync($classIds);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Việc nhập dữ liệu người dùng đã hoàn tất và '.number_format($import->successful_rows).' dòng đã được nhập.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' dòng đã bị lỗi.';
        }

        return $body;
    }
}
