<?php
   /*
   Plugin Name: Pens&eacute;e du jour
   Plugin URI: http://api.baoyam.com/wordpress/plugin/
   Description: Permet d'ajouter &agrave; votre site des proverbes ou des citations issus du site <a href="http://sagesse.baoyam.com">Baoyam Sagesse</a>. Ce plug-in installe un widget permetant d'afficher sur votre site le proverbe ou la citation du jour. Vous pouvez aussi afficher des proverbes ou des citations de fa&ccedil;on al&eacute;atoire.
   Version: 1.0
   Author: Dezmonde Agence Web
   Author URI: http://www.dezmonde.net
   License: GPL2 or later
   */
   
    /*
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
    */
   
    define('PENSEE_DU_JOUR_VERSION', '2.5.3');
    define('PENSEE_DU_JOUR_PLUGIN_URL', plugin_dir_url( __FILE__ ));
    
    function fctAPIURLDecode($strText){        
        $strOut = "" ;
	   $arrIn = explode("z",$strText);
       foreach($arrIn as $strElement){
        $strOut .= chr( (int)( hexdec($strElement)  )  ) ;
       }       
       return $strOut ;
	}
    
function fctWidgetPenseeDuJourDisplayBody($arrOptions){
  $intType = $arrOptions['type'] ;
  $strOutput = file_get_contents("http://baoyam.com/wisdom/fr/api-wp-plugin.php?key=rnYWeGokw782qsm7dHj946n0NAleoddd_jhqhhgd2wXbfgmsnrltA&type={$intType}&src=wp");
  echo "<div>" . fctAPIURLDecode( $strOutput ) . "</div>";
}

function fctWidgetPenseeDuJourDisplay($args) {
    extract($args);
    $options = get_option("widget_PenseeDuJour_Data");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'Pens&eacute;e du jour',
      'type' => 1
      );
  }
    echo $before_widget;
        echo $before_title;
            echo  $options['title'] ; 
        echo $after_title; 
        fctWidgetPenseeDuJourDisplayBody($options);
    echo $after_widget;    
}
 
function fctWidgetPenseeDuJourInit(){
  //register_sidebar_widget(__('Pens&eacute;e du jour'), 'fctWidgetPenseeDuJourDisplay');    
  
  wp_register_sidebar_widget(
    'widget_Pensee_Du_Jour',        // your unique widget id
    'Pens&eacute;e du jour',          // widget name
    'fctWidgetPenseeDuJourDisplay',  // callback function
    array(                  // options
        'description' => "Affiche le proverbe ou la citation du jour. Affiche aussi des proverbes ou citations de fa&ccedil;on al&eacute;atoire. "
    )
);

  //register_widget_control('Pens&eacute;e du jour', 'fctWidgetPenseeDuJourControl', 300, 200 );
  
   wp_register_widget_control("widget_Pensee_Du_Jour", 
        "Pens&eacute;e du jour", 
        "fctWidgetPenseeDuJourControl",
        array(
            "height" => 300, 
            "width" => 200
        )
   );
}

add_action("plugins_loaded", "fctWidgetPenseeDuJourInit");


function fctWidgetPenseeDuJourControl(){
    
     $arrOtionTypeStrings = array();
  
  $arrOtionTypeStrings[1] = <<<EOT
<option value="1" selected="selected">Proverbe du jour</option>
<option value="2">Proverbe al&eacute;atoire</option>
<option value="3">Citation du jour</option>
<option value="4">Citation al&eacute;atoire</option>
EOT;

$arrOtionTypeStrings[2] = <<<EOT
<option value="1" >Proverbe du jour</option>
<option value="2" selected="selected">Proverbe al&eacute;atoire</option>
<option value="3">Citation du jour</option>
<option value="4">Citation al&eacute;atoire</option>
EOT;

$arrOtionTypeStrings[3] = <<<EOT
<option value="1" >Proverbe du jour</option>
<option value="2">Proverbe al&eacute;atoire</option>
<option value="3" selected="selected">Citation du jour</option>
<option value="4">Citation al&eacute;atoire</option>
EOT;

$arrOtionTypeStrings[4] = <<<EOT
<option value="1" >Proverbe du jour</option>
<option value="2">Proverbe tir&eacute; au hasard</option>
<option value="3">Citation du jour</option>
<option value="4" selected="selected">Citation tir&eacute;e au hasard</option>
EOT;


  $options = get_option("widget_PenseeDuJour_Data");
  if (!is_array( $options )){
$options = array(
      'title' => 'Proverbe du jour',
      'type' => 1
      );
  }
 
  if ($_POST['widget_PenseeDuJour_Data-Submit'])  {
    $options['title'] = htmlspecialchars($_POST['widget_PenseeDuJour_Data-WidgetTitle']);
    $options['type'] = $_POST['widget_PenseeDuJour_Data-Type'];
    update_option("widget_PenseeDuJour_Data", $options);
  }
  $strOptionTitle = $options['title'] ;
  $strOptionType = $arrOtionTypeStrings[ $options['type'] ] ;
 

  $strMesssage = <<<EOT
<p>
    <div>
        <label for="widget_PenseeDuJour_Data-WidgetTitle">Titre : </label>
        <input type="text" id="widget_PenseeDuJour_Data-WidgetTitle" name="widget_PenseeDuJour_Data-WidgetTitle" value="$strOptionTitle" />
    </div>
    <div>
    <label for="widget_PenseeDuJour_Data-Type">Type : </label>
    <select id="widget_PenseeDuJour_Data-Type" name="widget_PenseeDuJour_Data-Type">
        {$strOptionType}
    </select>
    </div>
    <input type="hidden" id="widget_PenseeDuJour_Data-Submit" name="widget_PenseeDuJour_Data-Submit" value="1" />
</p>
EOT;
    echo $strMesssage ;

}
?>