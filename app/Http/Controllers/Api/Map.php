<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Map
{
    public function getLinks($params = null)
    {
        $query = Link::select('links.*')
            ->where('is_visible', 1);

        if (!empty($params)) {


            $calc_param = 0;
            $param_prop = [];
            foreach ($params as $param) {

                //udogodnienia
                if (in_array($param['id'], [6, 4, 5, 8, 7, 3]) && $param['value'] == 1) {
                    $calc_param++;
                    $param_prop[] = $param['id'];
                }

                //potwierdzenie
                if ($param['id'] == 'confirmed') {
                    $query->leftJoin(DB::raw('(SELECT SUM(`votes`) AS `votes_sum`, `votable_id`, `votable_type` FROM `votes`) `sub_votes`'), function ($join) {
                        $join->on('sub_votes.votable_id', '=', 'links.id');
                        $join->where('sub_votes.votable_type', 'like', "%Link%");
                    });
                    $query->groupBy('sub_votes.votable_id');
                    $query->selectRaw('`sub_votes`.`votes_sum`');
                    $query->where('sub_votes.votes_sum', '>=', 3);
                }
            }

            //czy wszystkie paramatry
            if ($calc_param > 0) {
                $query->leftJoin('links_property_values', function ($join) {
                    $join->on('links_property_values.link_id', '=', 'links.id');
                });
                $query->whereIn('links_property_values.link_property_id', $param_prop);
                $query->havingRaw('sum(`links_property_values`.`value`)= ?', [(int)$calc_param]);
            }
        }

        // dd($query->toSql());

        return $this->geoJson($query->get());
    }

    public function getLink(int $link_id)
    {
        return response()->json([
            'modal_link' => view('api.modal_link', ['link_id' => $link_id])->render()
        ]);
    }

    public function geoJson($locales)
    {
        $original_data = json_decode($locales, true);
        $features = array();

        foreach ($original_data as $key => $value) {
            $features[] = array(
                'type' => 'Feature',
                'geometry' => array('type' => 'Point', 'coordinates' => array((float)$value['lng'], (float)$value['lat'])),
                'properties' => array('name' => $value['title'], 'id' => $value['id']),
            );
        };

        $allfeatures = array('type' => 'FeatureCollection', 'features' => $features);
        return json_encode($allfeatures, true);
    }
}
