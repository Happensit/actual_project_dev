<?php
/**
 * Created by PhpStorm.
 * User: Happensit
 * Date: 26.12.13
 * Time: 0:22
 */


drupal_add_js(drupal_get_path('theme', 'svetexpo'). '/js/jquery.mousewheel-3.0.6.pack.js', array(
        'type' => 'file',
        'scope' => 'header',
        'group' => JS_DEFAULT,
        'weight' => -1,
        'preprocess' => TRUE,
        'cache' => TRUE,
    ));
drupal_add_css(drupal_get_path('theme', 'svetexpo'). '/js/fancybox/jquery.fancybox.css');
drupal_add_js(drupal_get_path('theme', 'svetexpo'). '/js/fancybox/jquery.fancybox.pack.js', array(
        'type' => 'file',
        'scope' => 'header',
        'group' => JS_DEFAULT,
        'preprocess' => TRUE,
        'cache' => TRUE,
    ));
drupal_add_js(drupal_get_path('theme', 'svetexpo') . '/js/jquery.jcarousel.min.js');
drupal_add_css(drupal_get_path('theme', 'svetexpo') . '/style/bunners.css', array(
        'group' => CSS_DEFAULT,
        'preprocess' => FALSE
    ));

drupal_add_js('jQuery(document).ready(function(){

    jQuery("a.fancy").fancybox({
		titleShow : false,
        //closeBtn: false,
        //padding: 0,
        afterShow: function(){
            jQuery(".fancybox-image").on("click", function() {
                    jQuery.fancybox.close();
            });
        }
	});

	jQuery("a[rel=second_fancy]").fancybox({
		titleShow : false,
		//padding	: 0,
		loop: false,
        afterShow: function(){
            var groupLength = this.group.length;
            var thisIndex = this.index + 2; // index starts at "0"
            jQuery(".fancybox-image").on("click", function() {
                if (thisIndex > groupLength) {
                    jQuery.fancybox.close();
                } else {
                    thisIndex++;
                }
            });
        }
	});

	 jQuery(".gallery_img").jcarousel({
            wrap: "circular",
     });

        jQuery(".gallery_img").jcarouselAutoscroll({ autostart: true,  interval: 5000 });

        jQuery(".gallery_img-control-prev")
            .on("jcarouselcontrol:active", function() {
                jQuery(this).removeClass("inactive");
            })
            .on("jcarouselcontrol:inactive", function() {
                jQuery(this).addClass("inactive");
            })
            .jcarouselControl({
                target: "-=1"
            });

        jQuery(".gallery_img-control-next")
            .on("jcarouselcontrol:active", function() {
                jQuery(this).removeClass("inactive");
            })
            .on("jcarouselcontrol:inactive", function() {
                jQuery(this).addClass("inactive");
            })
            .jcarouselControl({
                target: "+=1"
            });

    jQuery("dl.tabs dt").click(function(){
        jQuery(this).siblings().removeClass("active").end()
            .next("dd").andSelf().addClass("active");
    });
    });', 'inline');