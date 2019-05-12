<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Movements extends Controller
{
    public function display()
    {
        return cache()->remember('floor-sheet-final', 1 * 60 * 60 * 4, function () {
            return $crawler = Goutte::request('POST', 'http://www.nepalstock.com/floorsheet', ['_limit' => 100000]);
            $floorSheet = collect([]);
            $crawler->filter('tr')->each(function ($node, $index) use (&$floorSheet) {

                if ($index < 2) {
                    return;
                }

                $arr = [];

                $node->filter('td')->each(function ($node, $index) use (&$arr) {
                    array_push($arr, $node->html());
                });

                $arrKeys = ['sn', 'number', 'code', 'buyer', 'seller', 'quantity', 'rate', 'total'];

                count($arr) == count($arrKeys) && $floorSheet->push(collect($arrKeys)->combine($arr));
            });

            return $floorSheet->filter(function ($sheet) {
                return $sheet->get('rate') > 220 && $sheet->get('total') > 15000;
            })->sortByDesc(function ($row) {
                return $row->get('total');
            })->take(50)->values();;
        })
    }
}
