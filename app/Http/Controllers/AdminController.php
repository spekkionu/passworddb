<?php namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Website;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index($website_id)
    {
        $website = Website::with('admin')->findOrFail($website_id);
        return $website->admin;
    }

    public function show($website_id, $id)
    {
        return Admin::where("id", $id)->where('website_id', $website_id)->firstOrFail();
    }

    public function create(Request $request, $website_id)
    {
        $website = Website::findOrFail($website_id);

        $this->validate($request, [
            'username' => ['max:100'],
            'password' => ['max:100'],
            'url' => ['max:255']
        ], [
            'username.required' => 'Username is required'
        ]);

        $admin = new Admin();
        $admin->fill($request->only('type', 'username', 'password', 'url', 'notes'));
        $website->admin()->save($admin);

        return response()->json($admin, 201);
    }

    public function update(Request $request, $website_id, $id)
    {
        $admin = Admin::where("id", $id)->where('website_id', $website_id)->firstOrFail();

        $this->validate($request, [
            'username' => ['max:100'],
            'password' => ['max:100'],
            'url' => ['max:255']
        ]);

        $admin->update($request->only('type', 'username', 'password', 'url', 'notes'));

        return response()->json($admin, 200);
    }

    public function destroy($website_id, $id)
    {
        $admin = Admin::where("id", $id)->where('website_id', $website_id)->firstOrFail();
        $admin->delete();

        return response()->json($admin, 204);
    }
}
