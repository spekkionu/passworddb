<?php

namespace App\Http\Controllers;

use App\Http\Requests\Data\AddDataRequest;
use App\Http\Requests\Data\UpdateDataRequest;
use App\Models\Data;
use App\Models\Section;
use App\Models\Site;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function create(Site $site, Section $section, AddDataRequest $request)
    {
        $data       = new Data($request->only(['name']));
        $data->data = $request->get('records', []);
        $section->data()->save($data);

        return redirect()->route('site', $site);
    }

    public function update(Site $site, Section $section, Data $data, UpdateDataRequest $request)
    {
        $data->name = $request->get('name');
        $data->data = $request->get('records', []);
        $data->save();

        return redirect()->route('site', $site);
    }

    public function destroy(Site $site, Section $section, Data $data)
    {
        $data->delete();
        $records = $section->data()->ordered()->pluck('id');
        Data::setNewOrder($records);

        return redirect()->route('site', $site);
    }

    public function move(Site $site, Section $section, Data $data, string $dir)
    {
        if ($dir === 'left') {
            $data->moveOrderUp();
        } else {
            $data->moveOrderDown();
        }

        return redirect()->route('site', $site);
    }
}
