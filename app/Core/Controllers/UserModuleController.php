<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Models\UserModule;
use Illuminate\Http\Request;

class UserModuleController extends Controller
{
    public function index(Request $request)
    {
        $modules = $request->user()->userModules()->get();

        return response()->json($modules);
    }

    public function activate(Request $request)
    {
        $request->validate([
            'module_name' => 'required|in:nutrition,fitness,weight_body,sleep_recovery,mental_wellness,habits_lifestyle,beauty_selfcare,preventive_wellness,womens_wellness,mens_wellness,family_wellness,workplace_wellness,coaching,connected_health,community,education',
        ]);

        $module = UserModule::updateOrCreate(
            [
                'user_id'     => $request->user()->id,
                'module_name' => $request->module_name,
            ],
            [
                'is_active'    => true,
                'activated_at' => now(),
            ]
        );

        return response()->json($module);
    }

    public function deactivate(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string',
        ]);

        $module = $request->user()->userModules()
            ->where('module_name', $request->module_name)
            ->first();

        if ($module) {
            $module->update(['is_active' => false]);
        }

        return response()->json(['message' => 'Module deactivated']);
    }
}
