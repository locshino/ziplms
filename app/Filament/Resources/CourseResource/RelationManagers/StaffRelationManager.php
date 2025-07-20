<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Enums\CourseStaffRole;
use App\Models\CourseStaffAssignment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StaffRelationManager extends RelationManager
{
    protected static string $relationship = 'staffAssignments';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $modelLabel = 'Nhân sự';
    protected static ?string $pluralModelLabel = 'Danh sách Nhân sự';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Nhân sự')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->validationMessages([
                        'unique' => 'Nhân sự này đã được phân công cho khóa học này rồi.',
                    ]),

                Forms\Components\Select::make('role_tag')
                    ->label('Vai trò trong khóa học')
                    ->options(CourseStaffRole::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordKey('id') // <--- XÓA DÒNG NÀY
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Họ và Tên')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tags.name')
                    ->label('Vai trò')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (CourseStaffAssignment $record, array $data) {
                        if (isset($data['role_tag'])) {
                            $record->syncTags([$data['role_tag']]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mountUsing(function (Forms\ComponentContainer $form, CourseStaffAssignment $record) {
                        $role = $record->tags->first()?->name;
                        $form->fill([
                            'user_id' => $record->user_id,
                            'role_tag' => $role,
                        ]);
                    })
                    ->after(function (CourseStaffAssignment $record, array $data) {
                        if (isset($data['role_tag'])) {
                            $record->syncTags([$data['role_tag']]);
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}