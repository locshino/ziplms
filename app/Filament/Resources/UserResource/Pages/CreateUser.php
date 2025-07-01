<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\RoleEnum;
use App\Filament\Resources\UserResource;
use App\Models\Role;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public ?string $role = null;

    public function mount(): void
    {
        $roleQuery = request()->query('role');

        // Xác thực vai trò từ URL
        if ($roleQuery && in_array($roleQuery, RoleEnum::values())) {
            $this->role = $roleQuery;
        }

        // Phải gọi parent::mount() trước khi thao tác với form
        parent::mount();

        // Điền dữ liệu vào form một cách trực tiếp
        if ($this->role) {
            $roleModel = Role::where('name', $this->role)->first();
            if ($roleModel) {
                // Sử dụng form->fill() để điền giá trị mặc định cho trường 'roles'
                $this->form->fill([
                    'roles' => $roleModel->id,
                ]);
            }
        }
    }

    /**
     * Chạy SAU KHI người dùng đã được tạo thành công.
     * Đây là nơi an toàn để gán vai trò, vì trường 'roles' đã được khóa và không gửi đi.
     */
    protected function afterCreate(): void
    {
        if ($this->role) {
            // $this->record chứa model User vừa được tạo.
            $this->record->assignRole($this->role);
        }
    }

    public function getTitle(): string
    {
        if ($this->role) {
            return __('Tạo :role mới', ['role' => Str::ucfirst($this->role)]);
        }

        return parent::getTitle();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tạo người dùng thành công')
            ->body('Người dùng mới đã được thêm vào hệ thống.');
    }
}
