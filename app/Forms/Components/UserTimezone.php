<?php

namespace App\Forms\Components;

use DateTime;
use DateTimeZone;
use Filament\Forms\Components\Field;
use Tapp\FilamentTimezoneField\Concerns\HasTimezoneOptions;

class UserTimezone extends Field
{

    protected string $view = 'forms.components.user-timezone';
public $options;
 public $timezone;
    public function getOptions(): array
    {
        $options = $this->getTimezones();

        $this->options = $options;
// dd($this->options);
        return $this->options;
    }

    public function getTimezones(): array
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $data = [];

        $now = new DateTime('now', new DateTimeZone($this->getTimezoneType()));

        foreach ($timezones as $timezone) {
            $offsets[] = $offset = $now->setTimezone(new DateTimeZone($timezone))->getOffset();

            $data[$timezone] = $this->getFormattedOffsetAndTimezone($offset, $timezone);
        }

        array_multisort($offsets, $data);
        return $data;
    }
}
