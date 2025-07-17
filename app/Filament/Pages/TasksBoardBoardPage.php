<?php

namespace App\Filament\Pages;

use App\Filament\Resources;
use App\Models\Task;
use Dvarilek\FilamentTableSelect\Components\Form\TableSelect;
use Filament\Actions\Action;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Relaticle\Flowforge\Filament\Pages\KanbanBoardPage;

class TasksBoardBoardPage extends KanbanBoardPage
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationLabel = 'Kanban';

    protected static ?string $title = 'Kanban';

    public function getSubject(): Builder
    {
        return Task::query()->with('assignee');
    }

    public function mount(): void
    {
        $this
            ->titleField('title')
            ->orderField('sort_order')
            ->columnField('status')
            ->columns([
                'todo' => 'To Do',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
            ])

            // Optional configuration
            ->descriptionField('description')
            ->orderField('order_column')
            ->columnColors([
                'todo' => 'blue',
                'in_progress' => 'yellow',
                'completed' => 'green',
            ])
            ->cardLabel('Task')
            ->pluralCardLabel('Tasks')
            ->cardAttributes([
                'due_date' => 'Due Date',
                'assignee.name' => 'Assigned To',
            ])
            ->cardAttributeColors([
                'due_date' => 'red',
                'assignee.name' => 'yellow',
            ])
            ->cardAttributeIcons([
                'due_date' => 'heroicon-o-calendar',
                'assignee.name' => 'heroicon-o-user',
            ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->required()
                ->placeholder('Enter task title')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),
            TableSelect::make('assigned_to_id')
                ->label('Assignee')
                ->relationship('assignee', 'name')
                ->tableLocation(Resources\UserResource::class)
                ->multiple(),
            Forms\Components\DatePicker::make('due_date'),
        ];
    }

    public function createAction(Action $action): Action
    {
        return $action
            ->iconButton()
            ->icon('heroicon-o-plus')
            ->modalHeading('Create Task')
            ->modalWidth('xl')
            ->form(function (Forms\Form $form) {
                $schema = array_merge($this->getFormSchema(), [
                    Forms\Components\Hidden::make('status')->default('todo'),
                ]);

                return $form->schema($schema);
            });
    }

    public function editAction(Action $action): Action
    {
        return $action
            ->modalHeading('Edit Task')
            ->modalWidth('xl')
            ->form(function (Forms\Form $form) {
                $schema = array_merge($this->getFormSchema(), [
                    Forms\Components\Select::make('status')
                        ->options([
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                        ])
                        ->required(),
                ]);

                return $form->schema($schema);
            });
    }
}
