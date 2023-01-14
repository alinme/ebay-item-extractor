<?php
namespace EbayItemExtractor;
use PHPHtmlParser\Dom;

class Extractor
  {
    function __construct() {
      add_action( 'admin_menu', [ $this, 'init_menu' ] );
    }

    function init_menu() {
      $page_title = 'eBay Item Extractor';
      $menu_title = 'Extractor';
      $capability = 'manage_options';
      $menu_slug  = 'ebay-item-extractor';
      $function   = [$this, 'indexshow'];
      $icon_url   = 'dashicons-chart-pie';
      $position   = 2;

      add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
  }
  public static function allSubStrPos($str, $del)
  {
      $searchArray = explode($del, $str);
      unset($searchArray[count($searchArray) - 1]);
      $positionsArray = [];
      $index = 0;
      foreach ($searchArray as $i => $s) {
          array_push($positionsArray, strlen($s) + $index);
          $index += strlen($s) + strlen($del);
      }
      return $positionsArray;
  }
    function indexshow() {
      $item = ['images' => []];
      if(isset($_POST) && isset($_POST['eix-url'])) {
        $dom = new Dom;
        $dom = @$dom->loadFromUrl($_POST['eix-url']);
        
        
        try { $item['title'] = @$dom->find('meta[itemprop=name]')->getAttribute('content'); } catch(\Exception $e) {}
        try { $item['url'] = @$dom->find('link[rel=canonical]')->getAttribute('href'); } catch(\Exception $e) {}
        try { $item['item'] = @explode('itm/', $item['url'])[1]; } catch(\Exception $e) {}
        try { $item['price'] = @$dom->find('span[itemprop="price"] > span')->text; } catch(\Exception $e) {}
        try { $item['iframe'] = @$dom->find('iframe#desc_ifr')->src; } catch(\Exception $e) {}
        try { $item['about'] = @$dom->find('div.x-about-this-item')->innerHTML; } catch(\Exception $e) {}
        try { $item['seller'] = @$dom->find('div.ux-seller-section__item--seller')->innerHTML; } catch(\Exception $e) {}
        try { $item['store'] = @$dom->find('div.d-stores-info-categories__container__info__section__title')->innerHTML; } catch(\Exception $e) {}
        try { $item['feedback'] = @$dom->find('div.d-stores-info-categories__container__info__section__item')->innerHTML; } catch(\Exception $e) {}
        
        try { 
          $imgs = $dom->find('button.ux-image-filmstrip-carousel-item > img');
          foreach($imgs as $img) {
            $item['images'][] = str_replace('s-l64.jpg', 's-l1600.jpg', $img->src);
          }
         } catch(\Exception $e) {}
        try { $item['desc'] = @$dom->loadFromUrl($item['iframe'])->outerHtml; } catch(\Exception $e) {}
      }
    ?>
      <style>
        .vim.x-about-this-item .ux-layout-section-module {
              padding: unset;
              margin-bottom: 16px;
              line-height: 1.285715;
          }
          .vim .section-title {
            align-items: baseline;
            display: flex;
        }
      </style>
      <div class="wrap">
          <h1>eBay item extractor</h1>
          <p>Extract data from an eBay item url.</p>
          <div id="dashboard-widgets-wrap">
              <div id="dashboard-widgets" class="metabox-holder">
                  <div class="postbox-container" style="width: 50%;">
                      <div class="meta-box-sortables">
                          <div id="dashboard_quick_press" class="postbox">
                              <div class="postbox-header">
                                  <h2 class="hndle"><span class="hide-if-no-js">Extract your eBay items</span></h2>
                              </div>
                              <div class="inside">
                                  <form name="eixpost" action="<?=htmlspecialchars($_SERVER["PHP_SELF"])?>?page=ebay-item-extractor" method="post" id="quick-press" class="initial-form hide-if-no-js">
                                      <div class="input-text-wrap" id="title-wrap">
                                          <label for="eix-url"> eBay item URL </label>
                                          <input type="url" name="eix-url" id="eix-url" autocomplete="off" required="true" placeholder="https://www.ebay.com/itm/29xxxxxxxxxx" value="<?= isset($_POST['eix-url']) ? $_POST['eix-url'] : ''?>"/>
                                          <p id="eix-url-description">Try to use a shorter URL. Ex: <code>https://www.ebay.com/itm/29xxxxxxxxxx</code></p>
                                      </div>

                                      

                                      <p class="submit">
                                          <button type="submit" id="eix-submit" class="button button-primary">Extract now</button>
                                          <br class="clear" />
                                      </p>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
                  <?php if(count($item) > 1): ?>
                  <div class="postbox-container" style="width: 50%;">
                      <div class="meta-box-sortables">
                          <div id="dashboard_quick_press" class="postbox">
                              <div class="postbox-header">
                                  <h2 class="hndle" style="cursor: default; user-select: text;">
                                    <span>
                                      Seller info
                                    </span>
                                  </h2>
                              </div>
                              <div class="inside" style="margin: 12px;">
                                <?=isset($item['store']) ? '<h2>' . $item['store'] . '</h2>': '' ?>
                                <?=isset($item['seller']) ? 'Seller: ' . $item['seller'] : '' ?>
                                <?=isset($item['feedback']) ? '<br />' . $item['feedback'] : '' ?>
                                
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="postbox-container" style="width: 100%; display: block;">
                      <div class="meta-box-sortables">
                          <div id="dashboard_quick_press" class="postbox">
                              <div class="postbox-header">
                                  <h2 class="hndle" style="cursor: default; user-select: text;">
                                    <span>
                                      <?=isset($item['title']) ? $item['title'] : '' ?>
                                    </span>
                                    <span>
                                      <?=isset($item['price']) ? $item['price'] : '' ?>
                                    </span>
                                  </h2>
                              </div>
                              <div class="inside" style="margin: 12px;">
                                <div>
                                  <?php if(count($item['images']) > 0): ?>
                                    <table>
                                      <tr>
                                        <?php foreach($item['images'] as $image): ?>
                                          <td width="80px" align="center"><img src="<?=$image?>" alt="IMAGE" style="max-width: 120px;object-fit:cover; aspect-ratio: 4/3; border-radius: 10px;"></td>
                                        <?php endforeach; ?>
                                      </tr>
                                      <tr>
                                        <?php foreach($item['images'] as $image): ?>
                                          <td width="80px" align="center"><a href="<?=$image?>" alt="IMAGE" target="_blank" download>download</a></td>
                                        <?php endforeach; ?>
                                      </tr>
                                    </table>
                                    
                                  <?php endif; ?>
                                  
                                </div>
                                <div class="vim x-about-this-item">
                                  <?= isset($item['desc']) ? $item['desc'] : ''; ?>
                                  <hr />
                                  <?= isset($item['about']) ? $item['about'] : ''; ?>
                                  
                                </div>
                                
                              </div>
                          </div>
                      </div>
                  </div>
                  <?php endif; ?>

              </div>
          </div>
      </div>
    <?php }
  }

