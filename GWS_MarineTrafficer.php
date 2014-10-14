<?php
/**
 * Plugin Name: GWS_MarineTrafficer
 * Plugin URI: http://gwebs.se/GWS_MarineTrafficer
 * Description: Widget for displaying a minimap of your AIS enabled vessel at http://www.marinetraffic.com/
 * Author: Max Gebert (Gebert Web Solutions)
 * Author URI: http://gwebs.se
 * Version: 0.2
 * License: GPL2
 */

/*  Copyright 2014  Gebert Web Solutions  (email : max@gwebs.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Changelog: 
 * - V0.2: Added option for HTTP/HTTPS access method for the script to support https sites.
 */

class GWS_MarineTrafficer extends WP_Widget {

    function __construct() {
        parent::__construct(
                
            //Widget ID
            'GWS_MarineTrafficer', 

            // Widget name
            __('GWebS Marine Trafficer', 'GWS_MarineTrafficer'), 

            //Description
            array( 'description' => __( 'Widget for displaying a minimap of your AIS enabled vessel at http://www.marinetraffic.com/', 'GWS_MT' ), ) 
        );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        //Make sure vessel ID are set before displaying the map
        if($instance['trackvessel'] !== 0 && !is_null($instance['trackvessel']))
        {
            $showmenu = (esc_attr($instance['showmenu']) == "1" ? "true": "false");
            $maptype = (esc_attr($instance['showmenu']) == "4" ? "0": $instance['showmenu']);
            $accessmethod = (esc_attr($instance[ 'accessmethod' ]) == "http" ? "http": "https");
?>

<script>// <![CDATA[
    width='<?php echo esc_attr( $instance['width'] ); ?>';               //the width of the embedded map in pixels or percentage
    height='<?php echo esc_attr( $instance['height'] ); ?>';               //the height of the embedded map in pixels or percentage
    border='0';                 //the width of the border around the map (zero means no border)
    shownames='true';           //to display ship names on the map (true or false)
    zoom='<?php echo esc_attr( $instance['zoom'] ); ?>';                     //the zoom level of the map (values between 2 and 17)
    maptype='<?php echo esc_attr( $maptype ); ?>';                //use 0 for Normal map, 1 for Satellite, 2 for Hybrid, 3 for Terrain
    trackvessel='<?php echo esc_attr( $instance['trackvessel'] ); ?>';    //MMSI of a vessel (note: it will displayed only if within range of the system)
    remember='true';            //remember or not the last position of the map (true or false)
    language='en';              //the preferred display language
    showmenu='<?=$showmenu?>';            //show or hide thee map options menu
// ]]></script>
<script src="<?=$accessmethod?>://www.marinetraffic.com/js/embed.js"></script>

<?php
        }
        else {
            print("No data set for ".$title);
        }
        
        echo $args['after_widget'];
    }
		
// Widget Backend 
    public function form( $instance ) {
        
        // Set default values
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Marine Trafficer', 'GWS_MT' );
        }
        
        if ( isset( $instance[ 'width' ] ) ) {
            $width = $instance[ 'width' ];
        }
        else {
            $width = 300;
        }
        
        if ( isset( $instance[ 'height' ] ) ) {
            $height = $instance[ 'height' ];
        }
        else {
            $height = 200;
        }
        
        if ( isset( $instance[ 'trackvessel' ] ) ) {
            $trackvessel = $instance[ 'trackvessel' ];
        }
        else {
            $trackvessel = 0;
        }
        
        if ( isset( $instance[ 'showmenu' ] ) ) {
            $showmenu = $instance[ 'showmenu' ];
        }
        else {
            $showmenu = 0;
        }
        
        if ( isset( $instance[ 'zoom' ] ) ) {
            $zoom = $instance[ 'zoom' ];
        }
        else {
            $zoom = 9;
        }
        
        if ( isset( $instance[ 'maptype' ] ) ) {
            $maptype = $instance[ 'maptype' ];
        }
        else {
            $maptype = 4;
        }
        
        if ( isset( $instance[ 'accessmethod' ] ) ) {
            $accessmethod = $instance[ 'accessmethod' ];
        }
        else {
            $accessmethod = "https";
        }
        
        //Show the adminpanel
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id( 'trackvessel' ); ?>"><?php _e( 'Vessel ID:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'trackvessel' ); ?>" name="<?php echo $this->get_field_name( 'trackvessel' ); ?>" type="text" value="<?php echo esc_attr( $trackvessel ); ?>" /><br>
Set to 0 to disable.
</p>

<p>
<label for="<?php echo $this->get_field_id( 'showmenu' ); ?>"><?php _e( 'Show menu:' ); ?></label> 
<select class="widefat" id="<?php echo $this->get_field_id( 'showmenu' ); ?>" name="<?php echo $this->get_field_name( 'showmenu' ); ?>">
    <option name="showmenu_true" value="1"<?=(esc_attr( $showmenu ) == 1 ? " selected=\"selected\"":false)?>>Yes</option>
    <option name="showmenu_false" value="0"<?=(esc_attr( $showmenu ) == 0 ? " selected=\"selected\"":false)?>>No</option>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id( 'zoom' ); ?>"><?php _e( 'Zoom:' ); ?></label> 
<select class="widefat" id="<?php echo $this->get_field_id( 'zoom' ); ?>" name="<?php echo $this->get_field_name( 'zoom' ); ?>">
    <?php
    for ($i=2;$i<18;$i++)
    {
        print("<option name=\"zoom".$i."\"".($zoom == $i ? " selected": false).">".$i."</option>\n");
    }
    ?>
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id( 'maptype' ); ?>"><?php _e( 'Map type:' ); ?></label> 
<select class="widefat" id="<?php echo $this->get_field_id( 'maptype' ); ?>" name="<?php echo $this->get_field_name( 'maptype' ); ?>">
    <option name="maptype_4" value="4"<?=(esc_attr( $maptype ) == 4 ? " selected=\"selected\"":false)?>>Normal</option>
    <option name="maptype_1" value="1"<?=(esc_attr( $maptype ) == 1 ? " selected=\"selected\"":false)?>>Satellite</option>
    <option name="maptype_2" value="2"<?=(esc_attr( $maptype ) == 2 ? " selected=\"selected\"":false)?>>Hybrid</option>
    <option name="maptype_3" value="3"<?=(esc_attr( $maptype ) == 3 ? " selected=\"selected\"":false)?>>Terrain</option>  
</select>
</p>

<p>
<label for="<?php echo $this->get_field_id( 'accessmethod' ); ?>"><?php _e( 'Access method:' ); ?></label> 
<select class="widefat" id="<?php echo $this->get_field_id( 'accessmethod' ); ?>" name="<?php echo $this->get_field_name( 'accessmethod' ); ?>">
    <option name="accessmethod_http" value="http"<?=(esc_attr( $accessmethod ) == "http" ? " selected=\"selected\"":false)?>>HTTP</option>
    <option name="accessmethod_https" value="https"<?=(esc_attr( $accessmethod ) == "https" ? " selected=\"selected\"":false)?>>HTTPS</option>
</select>
</p>
<?php 
    }
	
// Update widget
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['width'] = ( ! empty( $new_instance['width'] ) ) ? strip_tags( $new_instance['width'] ) : '';
        $instance['height'] = ( ! empty( $new_instance['height'] ) ) ? strip_tags( $new_instance['height'] ) : '';
        $instance['trackvessel'] = ( ! empty( $new_instance['trackvessel'] ) ) ? strip_tags( $new_instance['trackvessel'] ) : '';
        $instance['showmenu'] = ( ! empty( $new_instance['showmenu'] ) ) ? strip_tags( $new_instance['showmenu'] ) : '';
        $instance['zoom'] = ( ! empty( $new_instance['zoom'] ) ) ? strip_tags( $new_instance['zoom'] ) : '';
        $instance['maptype'] = ( ! empty( $new_instance['maptype'] ) ) ? strip_tags( $new_instance['maptype'] ) : '';
        $instance['accessmethod'] = ( ! empty( $new_instance['accessmethod'] ) ) ? strip_tags( $new_instance['accessmethod'] ) : '';
        return $instance;
    }
} 

// Register the widget
function GWS_MT_load() {
	register_widget( 'GWS_MarineTrafficer' );
}
add_action( 'widgets_init', 'GWS_MT_load' );

?>