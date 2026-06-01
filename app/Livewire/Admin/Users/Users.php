<?php

namespace App\Livewire\Admin\Users;

use App\Models\Panel;
use App\Models\Plan;
use App\Models\User;
use App\Services\SubscriptionService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Users extends Component
{

public $users;
    public $viewModal = false;
    public $user;
    public $roles;
    public $role_name;
    public $UserModal = false;
    public $newUserName, $newUserEmail, $newUserPassword;
    public $search = '';
    public $panels;
    public $panelId;

    // User plan management
    public $plans = [];
    public $selectedPlanId;
    public $showPlanModal = false;
    public function mount()
    {
        $this->users = User::all();
        $this->roles = Role::all();
        $this->panels = Panel::all();
        $this->plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
    }
    public function updatedRoleName($value)
    {
        $this->role_name = $value;

        $user = $this->user;
        $user->syncRoles([$value]);


    }
    public function updatedPanelId($panel_id)
    {

        $this->role_name = $panel_id;
        $user = $this->user;
        if($panel_id == null) {
            $user->panels()->detach();
            return $this->dispatch('toast', [
                 'type' => 'success',
            'message' => 'User Panel removed successfully'
            ]);
        }
        $user->panels()->sync($panel_id);
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'User Panel updated successfully'
        ]);

    }


    public function render()
    {

        if (!empty($this->search)) {
            // dd($this->search);
            $this->users = User::where('name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                ->get();
        } else {

            $this->users = User::all();
        }
      return view('livewire.admin.users.users')->layout('layouts.admin.admin');
    }
    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return abort(404);
        }
        if($user->hasRole('superadmin')) {
              $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Superadmin cannot be deleted'
            ]);
            return;
        }
        if (file_exists($user->profile_photo_path)) {
            unlink($user->profile_photo_path);
        }
        $user->delete();
        $this->users = User::all();
    }
    public function viewUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return abort(404);
        }
        $this->user = $user;
        $this->role_name = $user?->roles?->first()?->name;
        $this->viewModal = true;
    }
    public function registerUser()
    {
        $this->validate([
            'newUserName' => 'required|min:3',
            'newUserEmail' => 'required|email',
            'newUserPassword' => 'required|min:8',
        ]);
        $user = User::create([
            'name' => $this->newUserName,
            'email' => $this->newUserEmail,
            'password' => bcrypt($this->newUserPassword),
        ]);
        $this->users = User::all();
        $this->UserModal = false;
    }

    // User Plan Management
    public function openPlanModal()
    {
        $this->selectedPlanId = $this->user->activePlan()?->id;
        $this->showPlanModal = true;
    }

    public function assignUserPlan()
    {
        if (!$this->selectedPlanId) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Please select a plan',
            ]);
            return;
        }

        $plan = Plan::find($this->selectedPlanId);
        if (!$plan) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Plan not found',
            ]);
            return;
        }

        app(SubscriptionService::class)->assignPlan($this->user, $plan);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => "User plan changed to {$plan->name}",
        ]);

        // Refresh user data
        $this->user = User::find($this->user->id);
        $this->showPlanModal = false;
    }

    public function cancelUserPlan()
    {
        app(SubscriptionService::class)->cancel($this->user);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'User subscription cancelled',
        ]);

        // Refresh user data
        $this->user = User::find($this->user->id);
        $this->showPlanModal = false;
    }

}
