<?php

namespace App\Observers;

use App\Models\Site;

class SiteObserver
{
    /**
     * Listen to the Site deleting event.
     *
     * @param  Site  $site
     * @return void
     */
    public function deleting(Site $site)
    {
        $site->sections()->delete();
    }
}
