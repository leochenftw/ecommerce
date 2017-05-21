<?php

use SaltedHerring\Debugger;
use SaltedHerring\RPC;

class CatcherController extends Page_Controller
{
    public function index()
    {
        // $hb = Config::inst()->get('New Zealand', "Hawke's Bay");
        // Debugger::inspect($hb);
        // $request = $this->request;
        // $what = RPC::fetch('http://www.trademe.co.nz/property');
        //
        // $dom = new DOMDocument();
        // @$dom->loadHTML($what);
        // $items = $dom->getElementById('PropertyRegionSelect');
        // foreach($items->childNodes as $item)
        // {
        //     if ($item->hasChildNodes()) {
        //         $childs = $item->childNodes;
        //         foreach($childs as $i) {
        //             // Debugger::inspect($i->nodeValue . ': ' . $item->getAttribute('value'), false);
        //             //http://www.trademe.co.nz/API/Ajax/HomepageSearches.aspx?rentOrSale=sale&propertyRegionId=9
        //             if (!empty($item->getAttribute('value'))) {
        //                 // Debugger::inspect($i->nodeValue . ': ' . $item->getAttribute('value'), false);
        //                 print "  '" . $i->nodeValue . "':\n";
        //                 $districts = RPC::fetch('http://www.trademe.co.nz/API/Ajax/HomepageSearches.aspx?rentOrSale=sale&propertyRegionId=' . $item->getAttribute('value'));
        //                 $districts = json_decode($districts);
        //                 $districts = $districts->optionlist;
        //                 foreach ($districts as $district)
        //                 {
        //                     if (!empty($district->value)) {
        //                         $id = $district->value;
        //                         $name = preg_replace('@\(.*?\)@', '', $district->displayName);
        //                         // Debugger::inspect("\t" . trim($name), false);
        //                         print "    '" . trim($name) . "':\n";
        //                         //http://www.trademe.co.nz/API/Ajax/HomepageSearches.aspx?rentOrSale=sale&propertyDistrictId=1
        //                         $suburbs = RPC::fetch('http://www.trademe.co.nz/API/Ajax/HomepageSearches.aspx?rentOrSale=sale&propertyDistrictId=' . $id);
        //                         $suburbs = json_decode($suburbs);
        //                         $suburbs = $suburbs->optionlist;
        //                         foreach ($suburbs as $suburb)
        //                         {
        //                             if (!empty($suburb->value)) {
        //                                 $name = preg_replace('@\(.*?\)@', '', $suburb->displayName);
        //                                 print "      - '" . trim($name) . "'\n";
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
    }
}
