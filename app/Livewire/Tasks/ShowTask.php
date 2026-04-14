<?php 

declare(strict_types=1);

namespace App\Livewire\Tasks;

use Livewire\Component;
use App\Models\Task;

final class ShowTask extends Component {

    public Task $task;

    public function mount(Task $task)
    {
        $this->task = $task;
    }

    public function render(){
        return view('livewire.tasks.show');
    }
 };