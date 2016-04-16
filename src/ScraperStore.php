<?php
/**
 * Created by PhpStorm.
 * User: fajarmawan
 * Date: 4/16/16
 * Time: 16:18
 */

namespace fajardm\ScraperStore;

use Goutte\Client;

class ScraperStore
{
    function __construct()
    {
        $time_out = 60;
        $this->client = new Client();
        $guzzleClient = new \GuzzleHttp\Client(array(
            'curl' => array(
                CURLOPT_TIMEOUT => $time_out,
            ),
        ));
        $this->client->setClient($guzzleClient);
    }

    public function matahariMall($url)
    {
        $crawler = $this->client->request('GET', $url);

        /**
         * List page
         */
        $product = $crawler->filter('.product-item-wrapper')->each(function ($node) {
            $product = new Product();
            $product->discount = 0;
            $product->discount_price = 0;
            $product->url = $node->filter('a')->attr('href');

            if (count($node->filter('.disc-box')) > 0) {
                $product->discount = $node->filter('.disc-box')->text();
                $product->normal_price = str_replace('Rp ', '', $node->filter('.item-price div')->first()->text());
                $product->discount_price = str_replace('Rp ', '', $node->filter('.item-price div')->last()->text());
            } else {
                $product->normal_price = str_replace('Rp ', '', $node->filter('.web-price')->text());
            }

            return $product;
        });

        /**
         * Detail page
         */
        for ($i = 0; $i < count($product); $i++) {
            $crawler = $this->client->request('GET', $product[$i]->url);
            if ($crawler->filter('.field-item')->count() > 0) {
                $product[$i]->url = 'www.mataharimall.com' . $product[$i]->url;
                $product[$i]->name = $crawler->filter('.field-item')->text();
                $detail = $crawler->filter('.well-sm li')->each(function ($node) {
                    return $node->text();
                });
                $product[$i]->detail = 'Detail Produk';
                foreach ($detail as $d) {
                    $product[$i]->detail = $product[$i]->detail . ', ' . $d;
                }

                $product[$i]->description = $crawler->filter('#product-details')->text();

                $images = $crawler->filter('#zoom-thumbs-slick a')->each(function ($node) {
                    return $node->attr('href');
                });
                $product[$i]->image = $images;

                //Seller
                $seller = new Seller();
                $seller->name = $crawler->filter('.store-info a')->text();
                $seller->url = 'www.mataharimall.com' . $crawler->filter('.store-info a')->attr('href');

                /**
                 * Seller page
                 */
                $crawler = $this->client->request('GET', $crawler->filter('.store-info a')->attr('href'));
                $seller->address = $crawler->filter('.store-location')->text();

                $product[$i]->seller = $seller;
            }
        }

        return $product;
    }

    public function elevenia($url)
    {
        $this->client = new Client();
        $crawler = $this->client->request('GET', $url);

        /**
         * List page
         */
        $product = $crawler->filter('.prodListType .itemList')->each(function ($node) {
            $product = new Product();
            //Product
            $product->url = $node->filter('a')->attr('href');
            $product->discount = str_replace('↓', '', $node->filter('.price span strong')->text());
            $product->normal_price = str_replace('Rp ', '', $node->filter('.price .notranslate')->first()->text());
            $product->discount_price = str_replace('Rp ', '', $node->filter('.price .notranslate')->last()->text());
            $product->name = $node->filter('.pordLink')->text();

            //Seller
            $seller = new Seller();
            $seller->name = $node->filter('.seller')->text();
            $seller->url = $node->filter('.seller a')->attr('href');

            $product->seller = $seller;

            return $product;
        });

        /**
         * Detail page
         */
        for ($i = 0; $i < 1; $i++) {
            $crawler = $this->client->request('GET', $product[$i]->url);

            if ($crawler->filter('#prdNo')->count() > 0) {
                if ($crawler->filter('.imgNav')->count() > 0) {
                    $images = $crawler->filter('.imgNav li a')->each(function ($node) {
                        return $node->attr('href');
                    });
                    $product[$i]->image = $images;
                } else {
                    $product[$i]->image = [$crawler->filter('#mainPrdImg a')->attr('href')];
                }

                $id = $crawler->filter('#prdNo')->attr('value');
                $crawler = $this->client->request('GET', 'http://www.elevenia.co.id/product/SellerProductDetail/getSellerProductDetailDesc.do?prdNo=' . $id);
                $table = $crawler->filter('table tr td table')->each(function ($node) {
                    $tr = $node->filter('tr')->each(function ($node) {
                        $td = $node->filter('td')->each(function ($node) {
                            return $node->text();
                        });
                        return $td;
                    });
                    return $tr;
                });
                $product[$i]->description = $table;
            }
        }

        return $product;
    }

    public function bhineka($url)
    {
        $this->client = new Client();
        $crawler = $this->client->request('GET', $url);

        /**
         * List page
         */
        $product = $crawler->filter('.prod-result-grid .prod-itm')->each(function ($node) {
            $product = new Product();
            $product->url = $node->filter('a')->attr('href');
            $product->name = $node->filter('.prod-itm-fullname')->text();
            if ($node->filter('.prod-itm-disc')->count() > 0) {
                $discount = $node->filter('.prod-itm-disc')->text();
                $product->discount = str_replace("-", '', $discount);
            }

            return $product;
        });

        /**
         * Detail page
         */
        for ($i = 0; $i < count($product); $i++) {
            $crawler = $this->client->request('GET', $product[$i]->url);
            $product[$i]->url = 'http://www.bhinneka.com' . $product[$i]->url;
            $detail_section = $crawler->filter('#prodInfoDetail');

            $product[$i]->detail = $detail_section->filter('.brdrTopSolid')->text();


            $price_Section = $detail_section->filter('.prodInfoSection')->eq(1);

            //Seller
            $seller = new Seller();
            $seller->name = $price_Section->filter('#ctl00_content_divProvidedBy a')->text();
            $seller->url = 'http://www.bhinneka.com' . $price_Section->filter('#ctl00_content_divProvidedBy a')->attr('href');
            $product[$i]->seller = $seller;

            //Price
            $product[$i]->normal_price = str_replace('Rp ', '', str_replace(',', '.', $price_Section->filter('#ctl00_content_divPrice')->text()));
            if ($price_Section->filter('#ctl00_content_divNormalPriceLowest')->count() > 0) {
                $normal_price = $price_Section->filter('#ctl00_content_divNormalPriceLowest')->text();
                $normal_price = explode('|', $normal_price);
                if (count($normal_price) > 1) {
                    $product[$i]->normal_price = str_replace('Rp ', '', str_replace(',', '.', $normal_price[0]));
                    $product[$i]->discount_price = str_replace('Rp ', '', str_replace(',', '.', $price_Section->filter('#ctl00_content_divPrice')->text()));
                } else {
                    $normal_price = $price_Section->filter('#ctl00_content_pnlNormalSave');
                    if ($price_Section->filter('#ctl00_content_pnlNormalSave')->count() > 0) {
                        $product[$i]->normal_price = str_replace('Rp ', '', str_replace(',', '.', $normal_price->filter('span')->text()));
                        $product[$i]->discount_price = str_replace('Rp ', '', str_replace(',', '.', $price_Section->filter('#ctl00_content_divPrice')->text()));
                    }
                }
            }

            //Description
            $product[$i]->description = ['overview' => null, 'specification' => null];
            $overview = $crawler->filter('#ctl00_content_rptTabContent_ctl00_pnlContent');
            if ($overview->count() > 0) {
                $overview = $overview->text();
                $product[$i]->description['overview'] = $overview;
            }
            $specification = $crawler->filter('#ctl00_content_rptTabContent_ctl01_pnlContent');
            if ($specification->count() > 0) {
                $tr = $specification->filter('table tr')->each(function ($node) {
                    $td = $node->filter('td')->each(function ($node) {
                        return $node->text();
                    });
                    return $td;
                });
                $specification = $tr;
                $product[$i]->description['specification'] = $specification;
            }

            //Image
            $images = $crawler->filter('#thumb img')->each(function ($node) {
                return $node->attr('src');
            });

            $product[$i]->image = $images;
        }

        return $product;
    }
}