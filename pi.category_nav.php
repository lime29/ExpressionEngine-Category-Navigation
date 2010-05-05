<?php
$plugin_info = array(
		'pi_name'			=> 'Category Navigation',
		'pi_version'		=> '1.0',
		'pi_author'			=> 'Paul Beardsell',
		'pi_author_url'		=> 'http://lime29.com/',
		'pi_description'	=> 'Creates links (next and previous within a category)',
		'pi_usage'			=> category_nav::usage()
	);


class Category_nav {

	function category_nav(){
		
	}

    function previous()
    {
       	global $DB, $TMPL, $OUT;

		                        
		$this_title = $TMPL->fetch_param('this_title');
		$category_id = $TMPL->fetch_param('category_id');

		
		$sql="		
			SELECT C.url_title
			FROM exp_weblog_data A INNER JOIN exp_category_posts B ON A.entry_id = B.entry_id INNER JOIN exp_weblog_titles C ON A.entry_id = C.entry_id
			WHERE B.cat_id = ".$category_id." AND C.status = 'open'
			ORDER BY C.entry_date DESC
		
		";
		
		
		$query = $DB->query($sql);
		
		$prev = "";
		
		$n = 0;
		
		foreach($query->result as $row){
				
			
			if($this_title == $row['url_title']){
				
				break;
			}
			
			$prev = $row['url_title'];
			
			
			$n++; 
		}
		
		return $prev;
		
    }

	function next()
    {
       	global $DB, $TMPL, $OUT;

		$this_title = $TMPL->fetch_param('this_title');
		$category_id = $TMPL->fetch_param('category_id');
		

		
		$out="";
		
		$sql="		
			SELECT A.entry_id, C.url_title
			FROM exp_weblog_data A INNER JOIN exp_category_posts B ON A.entry_id = B.entry_id INNER JOIN exp_weblog_titles C ON A.entry_id = C.entry_id
			WHERE B.cat_id = ".$category_id." AND C.status = 'open'
			ORDER BY C.entry_date DESC
		
		";
		
		$query = $DB->query($sql);
		
		$next = false;
		
		$n = 0;
		
		foreach($query->result as $row){
		
		
			if($next){
				$out = $row['url_title'];
				
				break;
			}
			
			
			if($this_title == $row['url_title']){
				
				$next = true;
			}
			
			
			$n++; 
		}
		
		return $out;
		
    }


    /* END */
    
// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>

Returns the previous url_title of in the same category:
{exp:category_nav:previous this_title="{url_title}" category_id = "4"}

Returns the next url_title of in the same category:
{exp:category_nav:next this_title="{url_title}" category_id = "4"}

Comparisons:
{if	"{exp:category_nav:next this_title="{url_title}" category_id = "4"}" != ""}
	{exp:category_nav:next this_title="{url_title}" category_id = "4"}
{if:else}
	No more entries
{/if}

<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
/* END */


}
// END CLASS
?>