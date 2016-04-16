<?php
/**
 * Created by PhpStorm.
 * User: fajarmawan
 * Date: 4/16/16
 * Time: 16:21
 */

namespace fajardm\ScraperStore;

class Scraper
{
    function store($name, $url)
    {
        $scrape = new ScraperStore();
        $result = null;
        switch ($name) {
            case 'matahari_mall' : {
                $result = $scrape->matahariMall($url);
                break;
            }
            case 'elevenia' : {
                $result = $scrape->elevenia($url);
                break;
            }
            case 'bhineka' : {
                $result = $scrape->bhineka($url);
                break;
            }
        }

        return $result;
    }
}