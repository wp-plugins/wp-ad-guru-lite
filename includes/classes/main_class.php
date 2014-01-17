<?php

class WPAdGuru {
public $options;
public $zones; #store object array of all active zones
public $ad_zone_links; #store all ad and zone links for current and default post.
public $ads; #store object array of all required ads for current and default post mentioned in ad zone links table.
public $all_country_list; #stroe all country code and name
public $page_type;#which type of page is being visited currently.
public $visitor_country_code; #store visitor's country code
public $instance_number=0; #index of current zone or ad block is being shown in page.



public function __construct()
	{
		global $wpdb; $wpdb->show_errors();
		add_action('admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'adguru_admin_enqueue_scripts') );
		add_action('admin_head', array( &$this, 'adguru_admin_head'));	
	
	}#end __construct
	public function print_msg($msg, $class="adguru_help_msg"){ echo '<div class="'.$class.'">'.$msg.'</div>';}
	
	public function adguru_admin_enqueue_scripts()
	{
		if(strpos($_SERVER['REQUEST_URI'], ADGURU_PLUGIN_SLUG)) #to ensure that current plugin page is being shown.
		{
		wp_enqueue_script( 'jquery' );
		$jquery_css_base = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css';
		wp_enqueue_style ( 'jquery-ui-standard-css', $jquery_css_base );
			
		}   
	}
	public function adguru_admin_head()
		{
			if(strpos($_SERVER['REQUEST_URI'], ADGURU_PLUGIN_SLUG)) #to ensure that current plugin page is being shown.
			{
			?>
			<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
			<script type="text/javascript">var ADGURU_ADMIN_API_URL="<?php echo ADGURU_ADMIN_API_URL;?>";</script>
			<script type="text/javascript" src="<?php echo ADGURU_PLUGIN_URL; ?>js/admin.js?var=<?php echo ADGURU_VERSION ?>"></script>		
			<link rel="stylesheet" href="<?php echo ADGURU_PLUGIN_URL; ?>css/style.css?var=<?php echo ADGURU_VERSION ?>" />
			<?php
			}
		}

    public function admin_menu()
    {   
        add_menu_page('Ad Guru', 'Ad Guru', 'manage_options', ADGURU_PLUGIN_SLUG , array( &$this, 'adguru_admin_page' ), ADGURU_PLUGIN_URL.'images/icon.png');
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Zones', 'Ad Zones', 'manage_options', 'adguru_adzones', array( &$this, 'ad_zones_page' ));
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Banner Ads', 'Banners', 'manage_options', 'adguru_banners', array( &$this, 'banners_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Set Bannter to Zone', 'Set Banner to Zone', 'manage_options', 'adguru_bannerzonelinks', array( &$this, 'bannerzonelinks_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Modal Popups', 'Modal Popups', 'manage_options', 'adguru_modal_popups', array( &$this, 'modal_popups_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Modal Popups to Pages', 'Set Modal Popups to pages', 'manage_options', 'adguru_modalpopuplinks', array( &$this, 'modalpopuplinks_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Window Popups', 'Window Popups', 'manage_options', 'adguru_window_popups', array( &$this, 'window_popups_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Window Popups to Pages', 'Set Window Popups to pages', 'manage_options', 'adguru_windowpopuplinks', array( &$this, 'windowpopuplinks_page' )); 
		add_submenu_page(ADGURU_PLUGIN_SLUG, 'Ad Guru Upgrade to Premium', 'Upgrade', 'manage_options', 'adguru_upgrade', 'adguru_upgrade_page'); 
    }
	public function adguru_admin_page()
	{
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/main_page.php");
	}
    public function ad_zones_page()
    {   
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/zones_main.php");
    }
    public function banners_page()
    {   
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/banners_main.php");
    }

    public function modal_popups_page()
    {   
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/modal_popups_main.php");
    }

    public function window_popups_page()
    {   
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/window_popups_main.php");
    }
	
	public function bannerzonelinks_page()
	{
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/ad_zone_links_settings.php");
	}
	public function modalpopuplinks_page()
	{
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/popup_links_settings.php");
	}
	public function windowpopuplinks_page()
	{
		include_once(ADGURU_PLUGIN_DIR."/includes/admin_pages/popup_links_settings.php");
	}	
	
	
	public function ad_zone_links_controller($arg=array(), $post_id=0)
	{

		include_once(ADGURU_PLUGIN_DIR."/includes/blocks/ad_zone_links_controller.php");
	
	}
	public function popup_links_controller($arg=array(), $post_id=0)
	{

		include_once(ADGURU_PLUGIN_DIR."/includes/blocks/popup_links_controller.php");
	
	}	
	
	public function is_permitted_user()
	{
		return current_user_can('manage_options');
	
	}
	
	public function print_permium_offer_box()
	{?>
	
		<div class="premium_offer_box" style="padding:10px; background:#ffffff; border:1px solid #eeeeee;">
		<h3>This is Premium Feature</h3>
		<a href="http://wpadguru.com" target="_blank"><input type="button" class="button-primary" value="BUY PREMIUM VERSION" /></a>
		</div>
	<?php 
	}
	
	#some common functoins=========================================================================
	public function is_valid_url($url)
	{ 
		return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+|localhost):?(\d+)?\/?/i', $url)) ? FALSE : TRUE;
	}
	
	public function curPageURL()
	{
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}

	public function get_country_list()
	{
		if(is_array($this->all_country_list)){return $this->all_country_list;}
		$country_list=array("--","---Select A Country---");
		$this->all_country_list=$country_list;
		return $country_list;
	}

	public function get_visitor_country_code()
	{
		if(!isset($this->visitor_country_code))
		{
		$this->visitor_country_code = "--";
		}
	return $this->visitor_country_code;
	}

	public function get_ad($id)
	{
		$id=intval($id);
		if(!$id){return false;}
		if(isset($this->ads[$id]))
			{
				return $this->ads[$id];
			}
			else
			{ 
			global $wpdb;
			$sql="SELECT * FROM ".ADGURU_ADS_TABLE." WHERE id=".$id;
			$ad =$wpdb->get_row($sql, OBJECT);
			if($ad){$this->ads[$id]=$ad; return $ad;}else{return false;}			
			}
	}

	public function get_zone($id)
	{
		$id=intval($id);
		if(!$id){return false;}
		$zones=$this->get_zones();
		if(isset($zones[$id]))
		{
			return $zones[$id];
		}
		else
		{
		global $wpdb;
		$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE id=".$id;
		$zone =$wpdb->get_row($sql, OBJECT);
		if(!$zone){return false;}else{$this->zones[$id]=$zone;return $zone;}		
		}
		
		return false;	
	}
			
	public function get_zones()
	{
		global $wpdb;
		if(!isset($this->zones))
		{
			$sql="SELECT * FROM ".ADGURU_ZONES_TABLE." WHERE active = 1";
			$this->zones=$wpdb->get_results($sql, OBJECT_K); #OBJECT_K - result will be output as an associative array of row objects, using first column's values as keys (duplicates will be discarded).
		}
		return $this->zones;
	
	}
	
	public function get_page_type()
	{
		if($this->page_type!="") return $this->page_type;		
		#Taking decision based on which type of page is being visited currently 
		$page_type="default";
		if(is_home() || is_front_page()){$page_type="home";}
		#End Taking decision 
		$this->page_type=$page_type;
		return $page_type;		
		
	}
	public function generate_ad_zone_and_links_data()
	{
		global $wpdb;
		$ad_zone_links=array(
							"banner"=>array( #ad_type
									0=>array( #zone_id
										"--"=>array( #page_type
											"--"=>array( #taxonomy
												"--"=>array( #term
													"0"=>array( #object_id
														"--"=>array( #country_code
															array( #slide number
																array("id"=>0, "percentage"=>100) #ad_item array
																)
															)
														)
													)
												)
											)
										)
									)
							);	
		
		$page_type = $this->get_page_type();
		$visitor_contry_code=$this->get_visitor_country_code();
		$zones=$this->get_zones();
		
		$zone_id_list=array(0);
		foreach($zones as $zone){$zone_id_list[]=$zone->id;}
		$zone_id_in=implode(",", $zone_id_list );
		if($visitor_contry_code=="" || $visitor_contry_code=="--" ){$country_code_in="'--'";}else{$country_code_in="'--','".$visitor_contry_code."'";}
				
		switch($page_type)
		{
			case "home":
			{
			$SQL="SELECT * FROM ".ADGURU_LINKS_TABLE." WHERE zone_id IN (".$zone_id_in.") AND page_type IN('home','--') AND object_id IN(0) AND country_code IN(".$country_code_in.")";
			
			break;
			}
			case "default":#not necessery
			{
			$SQL="SELECT * FROM ".ADGURU_LINKS_TABLE." WHERE zone_id IN (".$zone_id_in.") AND page_type IN('--') AND object_id IN(0) AND country_code IN(".$country_code_in.")";
			
			break;
			}															
									
		}#end switch
		
		$ad_zone_links_raw=$wpdb->get_results($SQL);
		 	
		 $ad_id_list=array();
		  if($ad_zone_links_raw)
		  {			
			foreach($ad_zone_links_raw as $links)
			{
				$ad_id_list[]=$links->ad_id;
				
				$ad_item=array("id"=>$links->ad_id, "percentage"=>$links->percentage);
				$slide=$links->slide;
				$ad_zone_links[$links->ad_type][$links->zone_id][$links->page_type][$links->taxonomy][$links->term][$links->object_id][$links->country_code][$slide-1][]=$ad_item;
			}
		  }
		  
		$this->ad_zone_links=$ad_zone_links; 
		if(count($ad_id_list))
		{
		$this->ads=$wpdb->get_results("SELECT * FROM ".ADGURU_ADS_TABLE." WHERE id IN(".implode(",",$ad_id_list).")", OBJECT_K);
		}
		else
		{
		$this->ads=array();
		}
	
	}#end function generate_ad_zone_and_links_data
	public function get_ad_zone_links()
	{
		if(!isset($this->ad_zone_links))
		{
			$this->generate_ad_zone_and_links_data();
		}
		return $this->ad_zone_links;
	
	}
	
	public function get_appropiate_ad_links($ad_type='banner', $zone_id=0)
	{
		$ad_zone_links=$this->get_ad_zone_links();
		if($ad_type=="banner")
		{
			if(!intval($zone_id))return false;
			$zones=$this->get_zones();
			if(!isset($zones[$zone_id])){return false;}		
			if(!isset($ad_zone_links['banner'])){return false;}
			if(!isset($ad_zone_links['banner'][$zone_id])){return false;}		
			$czl=$ad_zone_links['banner'][$zone_id]; // czl = current_zone_links
		}
		else
		{
			if(!isset($ad_zone_links[$ad_type])){return false;}
			$czl=$ad_zone_links[$ad_type][0]; // czl = current_zone_links
		}		
		
		$visitor_contry_code=$this->get_visitor_country_code();
		$links=array();

		$page_type = $this->get_page_type();		
		switch($page_type)
		{
			case "home":
			{
				if(isset($czl["home"]["--"]["--"][0][$visitor_contry_code]))
				{
					$links=$czl["home"]["--"]["--"][0][$visitor_contry_code];
				}
				if(isset($czl["home"]["--"]["--"][0]["--"]))
				{
					$links=$czl["home"]["--"]["--"][0]["--"];
				}
				elseif(isset($czl["--"]["--"]["--"][0][$visitor_contry_code]))
				{
					$links=$czl["--"]["--"]["--"][0][$visitor_contry_code];
				}								
				elseif(isset($czl["--"]["--"]["--"][0]["--"]))
				{
					$links=$czl["--"]["--"]["--"][0]["--"];
				}
				
			break;
			}
			case "default":
			{

				if(isset($czl["--"]["--"]["--"][0][$visitor_contry_code]))
				{
					$links=$czl["--"]["--"]["--"][0][$visitor_contry_code];
				}								
				elseif(isset($czl["--"]["--"]["--"][0]["--"]))
				{
					$links=$czl["--"]["--"]["--"][0]["--"];
				}
			
			break;
			}								
		}#end switch($page_type)
		return $links;
		
		
	}#end function get_appropiate_ad_links	
	
	
	public function get_ad_by_percentage_probability($ad_set)
	{
		if(!is_array($ad_set)){return false;}else{return $ad_set[0]["id"];}
	}#end function get_ad_by_percentage_probability	
	
#==========================================Seach=================================================================
	public function get_keywords_from_string($s)
	{
		
		//$s=strip_tags($s);
		$s=strtolower(trim($s));
		if($s=="")return false;
		
		#remove stop words http://en.wikipedia.org/wiki/Stop_words   http://www.webconfs.com/stop-words.php
		$stop_words=array( 
	"able", "about", "above", "abroad", "according", "accordingly", "across", "actually", "adj", "after", "afterwards", "again", "against", "ago", "ahead", "ain't", "all", "allow", "allows", "almost", "alone", "along", "alongside", "already", "also", "although", "always", "am", "amid", "amidst", "among", "amongst", "an", "and", "another", "any", "anybody", "anyhow", "anyone", "anything", "anyway", "anyways", "anywhere", "apart", "appear", "appreciate", "appropriate", "are", "aren't", "around", "as", "a's", "aside", "ask", "asking", "associated", "at", "available", "away", "awfully", "back", "backward", "backwards", "be", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begin", "behind", "being", "believe", "below", "beside", "besides", "best", "better", "between", "beyond", "both", "brief", "but", "by", "came", "can", "cannot", "cant", "can't", "caption", "cause", "causes", "certain", "certainly", "changes", "clearly", "c'mon", "co", "co.", "com", "come", "comes", "concerning", "consequently", "consider", "considering", "contain", "containing", "contains", "corresponding", "could", "couldn't", "course", "c's", "currently", "dare", "daren't", "definitely", "described", "despite", "did", "didn't", "different", "directly", "do", "does", "doesn't", "doing", "done", "don't", "down", "downwards", "during", "each", "edu", "eg", "eight", "eighty", "either", "else", "elsewhere", "end", "ending", "enough", "entirely", "especially", "et", "etc", "even", "ever", "evermore", "every", "everybody", "everyone", "everything", "everywhere", "ex", "exactly", "example", "except", "fairly", "far", "farther", "few", "fewer", "fifth", "first", "five", "followed", "following", "follows", "for", "forever", "former", "formerly", "forth", "forward", "found", "four", "from", "further", "furthermore", "get", "gets", "getting", "given", "gives", "go", "goes", "going", "gone", "got", "gotten", "greetings", "had", "hadn't", "half", "happens", "hardly", "has", "hasn't", "have", "haven't", "having", "he", "he'd", "he'll", "hello", "help", "hence", "her", "here", "hereafter", "hereby", "herein", "here's", "hereupon", "hers", "herself", "he's", "hi", "him", "himself", "his", "hither", "hopefully", "how", "howbeit", "however", "hundred", "i'd", "ie", "if", "ignored", "i'll", "i'm", "immediate", "in", "inasmuch", "inc", "inc.", "indeed", "indicate", "indicated", "indicates", "inner", "inside", "insofar", "instead", "into", "inward", "is", "isn't", "it", "it'd", "it'll", "its", "it's", "itself", "i've", "just", "k", "keep", "keeps", "kept", "know", "known", "knows", "last", "lately", "later", "latter", "latterly", "least", "less", "lest", "let", "let's", "like", "liked", "likely", "likewise", "little", "look", "looking", "looks", "low", "lower", "ltd", "made", "mainly", "make", "makes", "many", "may", "maybe", "mayn't", "me", "mean", "meantime", "meanwhile", "merely", "might", "mightn't", "mine", "minus", "miss", "more", "moreover", "most", "mostly", "mr", "mrs", "much", "must", "mustn't", "my", "myself", "name", "namely", "nd", "near", "nearly", "necessary", "need", "needn't", "needs", "neither", "never", "neverf", "neverless", "nevertheless", "new", "next", "nine", "ninety", "no", "nobody", "non", "none", "nonetheless", "noone", "no-one", "nor", "normally", "not", "nothing", "notwithstanding", "novel", "now", "nowhere", "obviously", "of", "off", "often", "oh", "ok", "okay", "old", "on", "once", "one", "ones", "one's", "only", "onto", "opposite", "or", "other", "others", "otherwise", "ought", "oughtn't", "our", "ours", "ourselves", "out", "outside", "over", "overall", "own", "particular", "particularly", "past", "per", "perhaps", "placed", "please", "plus", "possible", "presumably", "probably", "provided", "provides", "que", "quite", "qv", "rather", "rd", "re", "really", "reasonably", "recent", "recently", "regarding", "regardless", "regards", "relatively", "respectively", "right", "round", "said", "same", "saw", "say", "saying", "says", "second", "secondly", "see", "seeing", "seem", "seemed", "seeming", "seems", "seen", "self", "selves", "sensible", "sent", "serious", "seriously", "seven", "several", "shall", "shan't", "she", "she'd", "she'll", "she's", "should", "shouldn't", "since", "six", "so", "some", "somebody", "someday", "somehow", "someone", "something", "sometime", "sometimes", "somewhat", "somewhere", "soon", "sorry", "specified", "specify", "specifying", "still", "sub", "such", "sup", "sure", "take", "taken", "taking", "tell", "tends", "th", "than", "thank", "thanks", "thanx", "that", "that'll", "thats", "that's", "that've", "the", "their", "theirs", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "there'd", "therefore", "therein", "there'll", "there're", "theres", "there's", "thereupon", "there've", "these", "they", "they'd", "they'll", "they're", "they've", "thing", "things", "think", "third", "thirty", "this", "thorough", "thoroughly", "those", "though", "three", "through", "throughout", "thru", "thus", "till", "to", "together", "too", "took", "toward", "towards", "tried", "tries", "truly", "try", "trying", "t's", "twice", "two", "un", "under", "underneath", "undoing", "unfortunately", "unless", "unlike", "unlikely", "until", "unto", "up", "upon", "upwards", "us", "use", "used", "useful", "uses", "using", "usually", "v", "value", "various", "versus", "very", "via", "viz", "vs", "want", "wants", "was", "wasn't", "way", "we", "we'd", "welcome", "well", "we'll", "went", "were", "we're", "weren't", "we've", "what", "whatever", "what'll", "what's", "what've", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "where's", "whereupon", "wherever", "whether", "which", "whichever", "while", "whilst", "whither", "who", "who'd", "whoever", "whole", "who'll", "whom", "whomever", "who's", "whose", "why", "will", "willing", "wish", "with", "within", "without", "wonder", "won't", "would", "wouldn't", "yes", "yet", "you", "you'd", "you'll", "your", "you're", "yours", "yourself", "yourselves", "you've", "zero"
		);
		
		$key_list=explode(" " , $s);
		$keys_temp = array_diff($key_list, $stop_words);
		$solid_keys=array();
		foreach($keys_temp as $key)
		{
			$key=trim($key);
			if($key) $solid_keys[]=$key;
		}
		if(count($solid_keys))
		return $solid_keys;
		else 
		return false;
	}#end lcl_get_keywords_from_string

#==========================================End Seach=============================================================  

#===============================END PAGE NAVI======================================		
	public function print_page_navi($num_records, $current_page=1, $rpp=10, $url="")
	{
		//echo $url.'<br>';
		//$num_records;	     #holds total number of record
		$page_size;		     #holds how many items per page
		$page=$current_page; #holds the curent page index
		$num_pages; 	     #holds the total number of pages
		$page_size = $rpp;   #holds record per page
		#wrapping
		if($num_records > $page_size){echo '<div class="adguru_page_navi">';}				
		#caluculate number of pages to display
		if(($num_records%$page_size))
		{
			$num_pages = (floor($num_records/$page_size) + 1);
		}else{
			$num_pages = (floor($num_records/$page_size));
		}


		if ($num_pages != 1)
		{
		if($page>$num_pages){$page=$num_pages;}
		if($page<1){$page=1;}
		#finding first and last page number to be shown. we want to show only 10 number in the navigation and current page will be in the middle.
			if($page<6)
			{
			$first_page=1;
			$last_page=10;
			}
			else
			{
			$first_page=$page-5;
			$last_page=$page+4;
			}
			if($last_page>$num_pages){$last_page=$num_pages;}
			if($last_page<=10){$first_page=1;}
			if($last_page>10 && ($last_page - $first_page)<9){$first_page=$last_page - 9;}
		#================
		
		
			if($page>1)
				{
				$prev_page=$page-1; 
				$url=add_query_arg(array('npage'=>$prev_page), $url);
				echo '<a href="'.$url.'">Previous</a> ';
				}
			for ($i = $first_page; $i <= $last_page; ++$i)
			{
				#if page is the same as the page being written to screen, don't write the link
				#page navigation logic is developed by "oneTarek" http://onetarek.com
				if ($i == $page)
				{
					echo "$i";	
				}
				else
				{
					$url=add_query_arg(array('npage'=>$i), $url);
					echo '<a href="'.$url.'">'.$i.'</a>';
	
				}
				if($i != $last_page)
				{
					echo " ";
				}
				
			}
			if($page<$num_pages)
				{
				$next_page=$page+1;
				$url=add_query_arg(array('npage'=>$next_page), $url);
				echo ' <a href="'.$url.'">Next</a>';
				}
		}
		
		if($num_records > $page_size){echo '</div>';}
		
	}
#===============================END PAGE NAVI======================================			
	

	private function check_ad_input_validation()
	{
		$msg="";
		$errors=array();
		if(trim($_POST['camp_name'])==""){$msg.="Please Enter a Banner Name<br>";$errors['camp_name']=true;}
		if(intval($_POST['width'])==0){$msg.="Please Enter Banner Width<br>";$errors['width']=true;}
		if(intval($_POST['height'])==0){$msg.="Please Enter Banner Height<br>";$errors['height']=true;}
		if($_POST['code_type']!="html"){$msg.="Please select HTML for code type<br>";$errors['code_type']=true;}
		$result=array('errors'=>$errors, 'message'=>$msg);
		return $result;
	}#end function check_ad_input_validation
	private function check_zone_input_validation()
	{
		$msg="";
		$errors=array();
		if(trim($_POST['zone_name'])==""){$msg.="Please Enter A Zone Name<br>";$errors['zone_name']=true;}
		if(intval($_POST['width'])==0){$msg.="Please Enter Zone Width<br>";$errors['width']=true;}
		if(intval($_POST['height'])==0){$msg.="Please Enter Zone Height<br>";$errors['height']=true;}
		
		$result=array('errors'=>$errors, 'message'=>$msg);
		return $result;
	}#end function check_zone_input_validation


	private function check_modal_popup_input_validation()
	{
		$msg="";
		$errors=array();
		if(trim($_POST['camp_name'])==""){$msg.="Please Enter a Popup Name<br>";$errors['camp_name']=true;}
		if(intval($_POST['width'])==0){$msg.="Please Enter Popup Width<br>";$errors['width']=true;}
		if(intval($_POST['height'])==0){$msg.="Please Enter Popup Height<br>";$errors['height']=true;}
		if($_POST['code_type']!="html"){$msg.="Please select HTML for code type<br>";$errors['code_type']=true;}
		$result=array('errors'=>$errors, 'message'=>$msg);
		return $result;
	}#end function check_modal_popup_input_validation

	private function check_window_popup_input_validation()
	{
		$msg="";
		$errors=array();
		if(trim($_POST['camp_name'])==""){$msg.="Please enter a popup name<br>";$errors['camp_name']=true;}
		if(intval($_POST['width'])==0){$msg.="Please enter popup width<br>";$errors['width']=true;}
		if(intval($_POST['height'])==0){$msg.="Please enter popup height<br>";$errors['height']=true;}
		if($_POST['code_type']!="link_in_iframe"){$msg.="Please select 'URL in window' for code type<br>";$errors['code_type']=true;}
		$result=array('errors'=>$errors, 'message'=>$msg);
		return $result;
	}#end function check_window_popup_input_validation

}#end class WPAdGuru

?>