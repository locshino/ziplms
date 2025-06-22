<?php

namespace App\Imports;

use App\Imports\Base\ExcelImporter;
use App\Imports\Concerns\IsSmallImport; // Using a preset for performance
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserImporter extends ExcelImporter implements IsSmallImport
{
    public function model(array $row): ?Model
    {
        /** @var User $user */
        $user = new User([
            'name' => $row['name'],
            'email' => $row['email'],
            // The 'hashed' cast on the User model will automatically hash this value.
            // The validation rules ensure the 'password' key exists and is not empty.
            'password' => $row['password'],
        ]);

        // Pass the role to the model so it can be assigned in the 'created' event.
        if ($this->roleToAssign) {
            $user->roleToAssignOnCreate = $this->roleToAssign;
        }

        // Pass the batch context to the model so the 'created' event can track success.
        // This property will not be persisted to the database.
        $user->importBatch = $this->importBatch;

        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
