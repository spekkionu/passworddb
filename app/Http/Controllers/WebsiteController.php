<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebsiteController
 *
 * @package App\Http\Controllers
 */
class WebsiteController extends Controller
{

    /**
     * List Websites
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $dir = mb_strtoupper($request->get('dir', 'ASC'));
        $search = $request->get('search');
        $limit = $request->get('limit', 10);
        if ($limit) {
            $websites = Website::search($search)->orderBy($sort, $dir)->simplePaginate($limit);
            $websites->setPath('/api/websites')->appends(['sort' => $sort, 'dir' => $dir, 'search' => $search, 'limit' => $limit]);
        } else {
            $websites = Website::search($search)->orderBy($sort, $dir)->all();
        }
        return response()->json($websites);
    }

    /**
     * Count Websites
     *
     * @param Request $request
     * @return Response
     */
    public function count(Request $request)
    {
        $search = $request->get('search');
        $count = Website::search($search)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Show a single website
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return Website::with('ftp', 'admin', 'controlpanel', 'database')->findOrFail($id);
    }

    /**
     * Create a new website
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:100', 'unique:websites,name'],
            'domain' => ['max:100'],
            'url' => ['max:255']
        ]);
        $website = Website::create($request->only('name', 'domain', 'url', 'notes'));

        return response()->json($website, 201);
    }

    /**
     * Update an existing website
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $website = Website::findOrFail($id);

        $this->validate($request, [
            'name' => ['required', 'max:100', 'unique:websites,name,' . $website->id],
            'domain' => ['max:100'],
            'url' => ['max:255']
        ]);

        $website->update($request->only('name', 'domain', 'url', 'notes'));

        return response()->json($website, 200);
    }

    /**
     * Delete an existing website
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();

        return response()->json($website, 204);
    }
}
