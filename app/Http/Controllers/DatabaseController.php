<?php namespace App\Http\Controllers;


use App\Models\Database;
use App\Models\Website;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function index($website_id)
    {
        $website = Website::with('database')->findOrFail($website_id);
        return $website->database;
    }

    public function show($website_id, $id)
    {
        return Database::where("id", $id)->where('website_id', $website_id)->firstOrFail();
    }

    public function create(Request $request, $website_id)
    {
        $website = Website::findOrFail($website_id);

        $this->validate($request, [
            'type' => ['required', 'max:20', 'in:mysql,sqlite,mssql,oracle,pgsql,access,other'],
            'hostname' => ['max:255'],
            'username' => ['max:100'],
            'password' => ['max:255'],
            'database' => ['max:100'],
            'url' => ['max:255']
        ]);

        $database = new Database();
        $database->fill($request->only('type', 'hostname', 'username', 'password', 'database', 'url', 'notes'));
        $website->database()->save($database);

        return response()->json($database, 201);
    }

    public function update(Request $request, $website_id, $id)
    {
        $database = Database::where("id", $id)->where('website_id', $website_id)->firstOrFail();

        $this->validate($request, [
            'type' => ['required', 'max:20', 'in:mysql,sqlite,mssql,oracle,pgsql,access,other'],
            'hostname' => ['max:255'],
            'username' => ['max:100'],
            'password' => ['max:255'],
            'database' => ['max:100'],
            'url' => ['max:255']
        ]);

        $database->update($request->only('type', 'hostname', 'username', 'password', 'database', 'url', 'notes'));

        return response()->json($database, 200);
    }

    public function destroy($website_id, $id)
    {
        $database = Database::where("id", $id)->where('website_id', $website_id)->firstOrFail();
        $database->delete();

        return response()->json($database, 204);
    }
}
