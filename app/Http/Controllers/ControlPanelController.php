<?php namespace App\Http\Controllers;

use App\Models\ControlPanel;
use App\Models\Website;
use Illuminate\Http\Request;

class ControlPanelController extends Controller
{
    public function index($website_id)
    {
        $website = Website::with('controlpanel')->findOrFail($website_id);
        return $website->controlpanel;
    }

    public function show($website_id, $id)
    {
        return ControlPanel::where("id", $id)->where('website_id', $website_id)->firstOrFail();
    }

    public function create(Request $request, $website_id)
    {
        $website = Website::findOrFail($website_id);

        $this->validate($request, [
            'username' => ['max:100'],
            'password' => ['max:100'],
            'url' => ['max:255']
        ]);

        $controlpanel = new ControlPanel();
        $controlpanel->fill($request->only('username', 'password', 'url', 'notes'));
        $website->controlpanel()->save($controlpanel);

        return response()->json($controlpanel, 201);
    }

    public function update(Request $request, $website_id, $id)
    {
        $controlpanel = ControlPanel::where("id", $id)->where('website_id', $website_id)->firstOrFail();

        $this->validate($request, [
            'username' => ['max:100'],
            'password' => ['max:100'],
            'url' => ['max:255']
        ]);

        $controlpanel->update($request->only('username', 'password', 'url', 'notes'));

        return response()->json($controlpanel, 200);
    }

    public function destroy($website_id, $id)
    {
        $controlpanel = ControlPanel::where("id", $id)->where('website_id', $website_id)->firstOrFail();
        $controlpanel->delete();

        return response()->json($controlpanel, 204);
    }
}
