<?php namespace App\Http\Controllers;


use App\Models\FTP;
use App\Models\Website;
use Illuminate\Http\Request;

class FTPController extends Controller
{
    public function index($website_id)
    {
        $website = Website::with('ftp')->findOrFail($website_id);
        return $website->ftp;
    }

    public function show($website_id, $id)
    {
        return FTP::where("id", $id)->where('website_id', $website_id)->firstOrFail();
    }

    public function create(Request $request, $website_id)
    {
        $website = Website::findOrFail($website_id);

        $this->validate($request, [
            'type' => ['required', 'max:20', 'in:ftp,sftp,ftps,webdav,other'],
            'hostname' => ['max:255'],
            'username' => ['max:100'],
            'password' => ['max:255'],
            'path' => ['max:255']
        ]);

        $ftp = new FTP();
        $ftp->fill($request->only('type', 'hostname', 'username', 'password', 'path', 'notes'));
        $website->ftp()->save($ftp);

        return response()->json($ftp, 201);
    }

    public function update(Request $request, $website_id, $id)
    {
        $ftp = FTP::where("id", $id)->where('website_id', $website_id)->firstOrFail();

        $this->validate($request, [
            'type' => ['required', 'max:20', 'in:ftp,sftp,ftps,webdav,other'],
            'hostname' => ['max:255'],
            'username' => ['max:100'],
            'password' => ['max:255'],
            'path' => ['max:255']
        ]);

        $ftp->update($request->only('type', 'hostname', 'username', 'password', 'path', 'notes'));

        return response()->json($ftp, 200);
    }

    public function destroy($website_id, $id)
    {
        $ftp = FTP::where("id", $id)->where('website_id', $website_id)->firstOrFail();
        $ftp->delete();

        return response()->json($ftp, 204);
    }
}
