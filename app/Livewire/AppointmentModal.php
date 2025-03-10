<?php

namespace App\Livewire;

use Livewire\Component;

class AppointmentModal extends Component
{
    public $show = false;

    protected $listeners = ['openAppointmentModal' => 'open'];

    public function open()
    {
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.appointment-modal');
    }
} 