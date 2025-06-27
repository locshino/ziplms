<?php

namespace App\Imports;

use App\Imports\Base\ExcelImporter;
use App\Imports\Concerns\IsSmallImport; // Using a preset for performance
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\Failure;

class UserImporter extends ExcelImporter implements IsSmallImport
{
     public function model(array $row): ?Model
    {
        // STEP 1: Manually validate the row first to be absolutely sure.
        // This acts as a second line of defense.
        $validator = Validator::make($row, $this->rules());

        if ($validator->fails()) {
            // If validation fails here, we create a Failure object and skip.
            // The onFailure() method in the base class will handle it.
            $this->onFailure(new Failure(
                array_key_first($validator->errors()->messages()), // row index placeholder
                array_key_first($validator->errors()->messages()), // attribute
                $validator->errors()->all(), // error messages
                $row // row values
            ));

            return null; // IMPORTANT: Return null to skip creating the model.
        }

        // STEP 2: If validation passes, proceed with creating the user.
        $user = new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
        ]);

        // Pass context data to be used by the model event listener
        $user->importBatch = $this->importBatch;
        if ($this->roleToAssign) {
            $user->roleToAssignOnCreate = $this->roleToAssign;
        }

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
