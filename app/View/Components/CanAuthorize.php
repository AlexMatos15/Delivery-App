<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CanAuthorize extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $action,
        public mixed $model = null,
        public string $message = 'Você não tem permissão para realizar esta ação.'
    ) {
    }

    /**
     * Determine if the user is authorized for the action.
     */
    public function isAuthorized(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        if ($this->model) {
            return $user->can($this->action, $this->model);
        }

        return $user->can($this->action);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.can-authorize');
    }
}
