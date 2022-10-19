<?php
    /**
     * Plugin Name:       Incredible Post Slider
     * Description:       Stampa incredibili post slider
     * Version:           1.1.0
     * Requires at least: 5.2
     * Requires PHP:      7.2
     * Author:            Roberto Alecci
     * Author URI:        https://robertoalecci.github.io/
     * License:           GPL v2 or later
     * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
     * Text Domain:       incredible-post-slider
     * Domain Path:       /languages
     */

    /*---------------------------------------------------------------
    Controllo che l'esecuzione del file avvenga in Wordpress (No url o scripts)
    ---------------------------------------------------------------*/
    defined( 'ABSPATH' ) or die( 'No script!' );

    /*----------------------------------------------------------------------------------------
    Inclusioni script e stile
    ----------------------------------------------------------------------------------------*/
    if(! function_exists('incredible_post_slider_scripts')){
        function incredible_post_slider_scripts(){
            wp_enqueue_script('incredible-post-slider-owl-carousel', plugin_dir_url(__FILE__).'js/owl.carousel.min.js', array('jquery'), null, true);
            wp_enqueue_script('incredible-post-slider-scripts', plugin_dir_url(__FILE__).'js/scripts.js', array('jquery'), null, true);
        }
    }

    if(! function_exists('incredible_post_slider_styles')){
        function incredible_post_slider_styles(){
            wp_enqueue_style('incredible-post-slider-owl-carousel', plugin_dir_url(__FILE__).'css/owl.carousel.min.css');
	        wp_enqueue_style('incredible-post-slider-owl-theme', plugin_dir_url(__FILE__).'css/owl.theme.default.min.css');
            wp_enqueue_style('incredible-post-slider-scripts',  plugin_dir_url(__FILE__).'css/style.css');
        }
    }

    //Richiamo la funzione di inserimento scripts
    add_action('wp_enqueue_scripts', 'incredible_post_slider_scripts', 1);

    //Richiamo la funzione di inserimento scripts
    add_action('wp_enqueue_scripts', 'incredible_post_slider_styles', 1);

    /*----------------------------------------------------------------------------------------
    Shortcode
    ----------------------------------------------------------------------------------------*/
    if(! function_exists('incredible_post_slider_shortcode')){
        function incredible_post_slider_shortcode($atts=''){
            /*
            Parametri:
                - featured: categoria da estrarre per riempire il carosello
                - height-desktop: altezza dello slider su dekstop
                - height-mobile: altezza dello slider su mobile
                - slides: numero massimo di slide da inserire
            */

            //Salvo parametri shortcode
            $featured = (!empty($atts['featured']) ? $atts['featured'] : ' ');
            $height_desktop = (!empty($atts['height-desktop']) && is_numeric($atts['height-desktop']) ? $atts['height-desktop'] : '');
            $height_mobile = (!empty($atts['height-mobile']) && is_numeric($atts['height-mobile']) ? $atts['height-mobile'] : '');
            $slides = (!empty($atts['slides']) ? $atts['slides'] : 4);

            //Slider da restituire
            $slider = "";

            // The Query
            $estrai_articoli = new WP_Query( array( 'category_name' => $featured, 'posts_per_page' => $slides ) );
            
            if ($estrai_articoli->have_posts()){

                //Container
                $slider .= '<div class="incredible_post_slider_container">';

                    //Apro il carosello
                    $slider .= '<div class="incredible_post_slider owl-carousel owl-theme">';
                    
                        // The Loop
                        while ( $estrai_articoli->have_posts() ) {
                            //Estraggo il post
                            $estrai_articoli->the_post();
                            
                            //Estraggo l'immagine
                            $image = (has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large') : '');
                            
                            //Stampo il box
                            $slider .= '<div class="item" data-merge="2" style="background: url('.(!empty($image[0]) ? $image[0] : '').'), #f7f7f7;"><a href="'.get_permalink().'" title="'.get_the_title().'"><h2>'.get_the_title().'</h2></a></div>';
                        }
                    
                    //Chiudo il carosello
                    $slider .= '</div>';
                    
                    //Controlli custom per lo slider
                    $slider .= '<button class="customPrevBtn">'.file_get_contents(plugin_dir_url(__FILE__).'img/chevron-left.svg').'</button>';
                    $slider .= '<button class="customNextBtn">'.file_get_contents(plugin_dir_url(__FILE__).'img/chevron-right.svg').'</button>';
                
                $slider .= '</div>';

                //Aggiungo stile custom in base ai parametri
                $slider .= (!empty($height_desktop) ? '<style>:root { --slider-height-desktop: '.$height_desktop.'px; }</style>' : '');
                $slider .= (!empty($height_mobile) ? '<style>:root { --slider-height-mobile: '.$height_mobile.'px; }</style>' : '');
                
            } else {

                $slider .= '<p style="background: darkred; color: #fff; padding: 8px 16px;">Nessun articolo in evidenza. Per far comparire lo slider, assegna agli articoli una categoria, poi indica lo slug nello shortcode (es. featured="slug-categoria").</p>';

            }

            //Resetto query
            wp_reset_postdata();

	        //Restituisco lo slider
	        return $slider;
        }
        add_shortcode('incredible_post_slider', 'incredible_post_slider_shortcode');
    }