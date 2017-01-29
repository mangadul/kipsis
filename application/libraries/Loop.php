<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loop
{

    private static $CI;

    public function __construct()
    {
        self::$CI = & get_instance();
    }

    static function getCartItems($cartItems)
    {
        if (!empty($cartItems['array'])) {
            ?>
            <li class="cleaner text-right">
                <a href="javascript:void(0);" class="btn-blue-round" onclick="clearCart()">
                    <?= lang('clear_all') ?>
                </a>
            </li>
            <li class="divider"></li>
            <?php
            foreach ($cartItems['array'] as $cartItem) {
                ?>
                <li class="shop-item" data-artticle-id="<?= $cartItem['id'] ?>">
                    <span class="num_added hidden"><?= $cartItem['num_added'] ?></span>
                    <div class="item">
                        <div class="item-in">
                            <div class="left-side">
                                <img src="<?= base_url('/attachments/shop_images/' . $cartItem['image']) ?>" alt="" />
                            </div>
                            <div class="right-side">
                                <a href="<?= LANG_URL . '/' . $cartItem['url'] ?>" class="item-info">
                                    <span><?= $cartItem['title'] ?></span>
                                    <span class="currency"><?= CURRENCY ?></span>
                                    <span class="prices">
                                        <?=
                                        $cartItem['num_added'] == 1 ? $cartItem['price'] : '<span class="num-added-single">'
                                                . $cartItem['num_added'] . '</span> x <span class="price-single">'
                                                . $cartItem['price'] . '</span> - <span class="sum-price-single">'
                                                . $cartItem['sum_price'] . '</span>'
                                        ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="item-x-absolute">
                            <button class="btn btn-xs btn-danger pull-right" onclick="removeProduct(<?= $cartItem['id'] ?>)">
                                x
                            </button>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
            <li class="divider"></li>
            <li class="text-center">
                <a class="go-checkout btn btn-default btn-sm" href="<?= LANG_URL . '/checkout' ?>">
                    <?=
                    !empty($cartItems['array']) ? '<i class="fa fa-check"></i> '
                            . lang('checkout') . ' - <span class="finalSum">'. CURRENCY . $cartItems['finalSum']
                            . '</span>' : '<span class="no-for-pay">' . lang('no_for_pay') . '</span>'
                    ?>
                </a>
            </li>
        <?php } else {
            ?>
            <li class="text-center"><?= lang('no_products') ?></li>
            <?php
        }
    }

    static public function getProducts($products, $classes = '', $carousel = false)
    {
        if ($carousel == true) {
            ?>
            <div class="carousel slide" id="small_carousel" data-ride="carousel" data-interval="3000">
                <ol class="carousel-indicators">
                    <?php
                    $i = 0;
                    while ($i < count($products)) {
                        if ($i == 0)
                            $active = 'active';
                        else
                            $active = '';
                        ?>
                        <li data-target="#small_carousel" data-slide-to="<?= $i ?>" class="<?= $active ?>"></li>
                        <?php
                        $i++;
                    }
                    ?>
                </ol>
                <div class="carousel-inner">
                    <?php
                }
                $i = 0;
                foreach ($products as $article) {
                    if ($i == 0 && $carousel == true)
                        $active = 'active';
                    else
                        $active = '';

                    $disk_persen = 0;
                    $percent_friendly = "0";
                    if ($article['old_price'] != '' && $article['old_price'] != 0) {
                        $disk_persen = number_format((($article['old_price'] - $article['price']) / $article['old_price']) * 100);
                        $percent_friendly = $disk_persen . "%";
                    }

                    ?>
                    <div class="product-list <?= $carousel == true ? 'item' : '' ?> <?= $classes ?> <?= $active ?>">
                        <div class="inner">
                            <div class="img-container">
                                <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                                    <img src="<?= base_url('/attachments/shop_images/' . $article['image']) ?>" alt="<?= str_replace('"', "'", $article['title']) ?>">                                    
                                </a>
                                <?php if($disk_persen > 0) { ?>
                                <div class="buble bbpersen"><p>Diskon</p><b>
                                        <?= $percent_friendly ?>
                                </b>
                                </div>     
                                <?php } ?>                                                       
                            </div>
                            <h2>
                                <a href="<?= LANG_URL . '/' . $article['url'] ?>"><?= character_limiter($article['title'], 70) ?></a>
                            </h2>
                            <div class="price">
                                <span class="underline"><?= CURRENCY ?> <span><?= $article['price'] != '' ? number_format($article['price'], 0) : 0 ?></span></span>
                                <!--
                                <?php
                                if ($article['old_price'] != '' && $article['old_price'] != 0) {
                                    $percent_friendly = number_format((($article['old_price'] - $article['price']) / $article['old_price']) * 100) . '%';
                                    ?>
                                    <span class="price-down"><?= $percent_friendly ?></span>
                                <?php } ?>
                                -->
                            </div>
                            <div class="price-discount <?= ($article['old_price'] == '' || $article['old_price'] == $article['price'])  ? 'invisible' : '' ?>">
                                <!--<?= lang('old_price') ?>:--><span style="text-decoration: line-through;"><?=CURRENCY?><?= $article['old_price'] != '' ? number_format($article['old_price'], 0): '' ?></span>
                            </div>
                            <?php if (self::$CI->load->get_var('publicQuantity') == 1) { ?>
                                <div class="quantity">
                                    <?= lang('in_stock') ?>: <span><?= $article['quantity'] ?></span>
                                </div>
                            <?php } if (self::$CI->load->get_var('moreInfoBtn') == 1) { ?>
                                <a href="<?= LANG_URL . '/' . $article['url'] ?>" class="info-btn gradient-color">
                                    <span class="text-to-bg"><?= lang('info_product_list') ?></span>
                                </a>
                            <?php } ?>

                            <div class="col-item">
                                <div class="add-to-cart">                                
                                    <a href="javascript:void(0);" class="add-to-cart btn-add" data-goto="<?= LANG_URL . '/shopping-cart' ?>" data-id="<?= $article['id'] ?>">
                                        <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                        <span class="text-to-bg"><i class="fa fa-shopping-cart"></i> Tambah</span>
                                        <!--<?= lang('add_to_cart') ?>-->
                                    </a>
                                </div>
                                <div class="add-to-cart">
                                    <a href="javascript:void(0);" class="add-to-cart btn-add more-blue" data-goto="<?= LANG_URL . '/checkout' ?>" data-id="<?= $article['id'] ?>">
                                        <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                        <span class="text-to-bg"><i class="fa fa-list"></i> Beli</span>
                                        <!-- <?= lang('buy_now') ?> -->
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                if ($carousel == true) {
                    ?>
                </div>
                <a class="left carousel-control" href="#small_carousel" role="button" data-slide="prev">
                    <i class="fa fa-5x fa-angle-left" aria-hidden="true"></i>
                </a>
                <a class="right carousel-control" href="#small_carousel" role="button" data-slide="next">
                    <i class="fa fa-5x fa-angle-right" aria-hidden="true"></i>
                </a>
            </div>
            <?php
        }
    }

}
