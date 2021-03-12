<?php

namespace App\Http\Livewire\Dashboard;

use App\Actions\CreatePdfDataSource;
use Livewire\Component;

class MeetingPdfWidget extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('livewire.dashboard.meeting-pdf-widget');
    }

    public function generatePdf()
    {
        CreatePdfDataSource::forWeekendMeetings();
    }
}
