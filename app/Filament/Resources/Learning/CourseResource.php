<?php

namespace App\Filament\Resources\Learning;

use App\Filament\Resources\Learning\CourseResource\Pages;
use App\Filament\Resources\Learning\CourseResource\RelationManagers;
use App\Models\Learning\Course;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\ValidationException;
use IlluminateAgnostic\Arr\Support\HtmlString;
use Symfony\Contracts\Service\Attribute\Required;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Create New Course')


                        ->beforeValidation(function (Set $set) {
                            if (is_null(session()->get('videos'))) {
                                throw ValidationException::withMessages(['video' => 'Video is required']);
                            } else {
                                $set('VideoList', session()->get('videos'));
                            }
                        })
                        ->schema([
                            Section::make('')

                                ->schema([
                                    TextInput::make('title')
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('instructor')
                                        ->maxLength(255)

                                        ->required(),
                                    FileUpload::make('image')
                                        ->image()
                                        ->maxSize(2000)
                                        ->helperText('Please select a image that is smaller than 2MB in size.')

                                        ->columnSpan('full')
                                        ->required(),

                                    Select::make('user_id')
                                        ->label('Assignee')
                                        ->options(User::all()->pluck('name', 'id'))
                                        ->required()
                                        // ->default([2])
                                        ->multiple()->searchable(),
                                    Select::make('quiz_time')
                                        ->options([
                                            10 => 10,
                                            20 => 20,
                                            30 => 30,
                                            40 => 40,
                                            60 => 60,
                                            80 => 80,
                                            100 => 100,
                                            120 => 120,
                                        ])
                                        ->suffix('Minutes')
                                        ->required(),
                                    Textarea::make('description')
                                        ->columnSpan('full')
                                        ->required(),
                                ])
                                ->columns(2),
                            Section::make('Videos')
                                ->hiddenOn('edit')
                                ->schema([


                                    ViewField::make('VideoList')
                                        ->label('')

                                        ->view('forms.components.course-video-upload')

                                ])
                        ])
                        ->columns(2),
                    Wizard\Step::make('Create Quiz')
                        ->hiddenOn('edit')
                        ->schema([
                            Repeater::make('Quiz Questions')
                                ->schema([
                                    RichEditor::make('question')
                                        ->required(),

                                    Repeater::make('options')

                                        ->defaultItems(3)
                                        ->schema([
                                            Checkbox::make('is_correct')
                                                ->fixIndistinctState()
                                                ->rules([
                                                    fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                                        $correctAnswers = $get('../../options');
                                                        $totalAnswer = 0;
                                                        // dd($correctAnswers);
                                                        foreach ($correctAnswers as $correctAnswer) {
                                                            if ($correctAnswer['is_correct']) {
                                                                $totalAnswer += 1;
                                                            }
                                                        }
                                                        if ($totalAnswer > 1 || $totalAnswer == 0) {
                                                            $fail("You must select only one answer");
                                                        }
                                                    },
                                                ])
                                                ->inline(false)
                                                ->label(function (Get $get) {
                                                    $letter = 'A';
                                                    $correctAnswers = $get('../../options');
                                                    // chr(ord($letter) + 1)
                                                    return 'Correct';
                                                }),


                                            TextInput::make('option')
                                                ->maxLength(255)->required(),


                                        ])->columns(2)

                                ])
                        ]),


                ])
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
    <x-filament::button
        type="submit"
        size="sm"
    >
        Submit
    </x-filament::button>
BLADE)))->hiddenOn('edit'),
                Section::make('')

                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('instructor')

                            ->required(),
                        FileUpload::make('image')
                            ->image()
                            ->maxSize(2000)
                            ->helperText('Please select a image that is smaller than 2MB in size.')

                            ->columnSpan('full')
                            ->required(),

                        Select::make('user_id')
                            ->label('Assignee')
                            ->options(User::all()->pluck('name', 'id'))
                            ->required()
                            // ->default([2])
                            ->multiple()->searchable(),
                        Select::make('quiz_time')
                            ->options([
                                10 => 10,
                                20 => 20,
                                30 => 30,
                                40 => 40,
                                60 => 60,
                                80 => 80,
                                100 => 100,
                                120 => 120,
                            ])
                            ->suffix('Minutes')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpan('full')
                            ->required(),
                    ])
                    ->visibleOn('edit')
                    ->columns(2),
                Section::make('Videos')
                    ->visibleOn('edit')
                    ->schema([


                        ViewField::make('VideoList')
                            ->label('Video')

                            ->view('forms.components.list-video')

                    ])


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([

                'xl' => 3,
            ])
            ->columns([
                Split::make([
                    Stack::make([
                        ImageColumn::make('image')
                            ->circular(),
                        TextColumn::make('title'),
                        TextColumn::make('description')
                            ->color('gray'),
                    ])
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LearningEmployeeRelationManager::class,

        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $query->where('created_by', auth()->id());


        return $query;
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            // 'view' => Pages\ViewCourse::route('/{record}'),
        ];
    }
}
