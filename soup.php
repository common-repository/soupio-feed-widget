<?php
/*
Plugin Name: Soup.io feed
Plugin URI: http://www.tchew.biz/plugins
Description: The soup.io feed plugin allows you to place a feed from your soup.io account in the sidebar. You can configure the soup.io user, size and number of items and a custom style sheet.
Author: Matt Hammond
Version: 1
Author URI: http://www.tchew.biz
*/

function soupWidget()
{
	$options = get_option("widget_soupFeed");
	if (!is_array( $options ))
	{
	$options = array(
		'soup-userid' => ''
	  );
	}
  
  echo '<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
 
    google.load("feeds", "1");
    </script>
    <script type="text/javascript">
 
    var feed = new google.feeds.Feed("http://' . $options['soup-userid'] . '.soup.io/rss");
	feed.load(function(result) {
	  if (!result.error) {
	    var container = document.getElementById("feed");
	    for (var i = 0; i < result.feed.entries.length; i++) {
	      var entry = result.feed.entries[i];
	      var d = new Date(entry.publishedDate);
	      var min = d.getMinutes();
	      min = min + "";
	      if (min.length === 1)
			{
			min = "0" + "" + min;
			};
	      newDiv = document.createElement("li");
	      newDiv.innerHTML = "<a href=\'" + entry.link + "\'>" + entry.title + "</a><br />" + d.getHours() + ":" + min + " " + d.toLocaleDateString();
	      container.appendChild(newDiv);
	    }
	  }
	});
 
    </script>
    <div id="soup"><h3 class="widgettitle"><img style="vertical-align:middle; margin-right: 5px;" src="http://' . $options['soup-userid'] . '.soup.io/images/favicon.png" />' . $options['soup-userid'] . '\'s Soup</h3><ul id="feed"></ul></div>';
}

function widget_soupFeed($args) {
  extract($args);
  echo $before_widget;
  echo $before_title; 
  echo $after_title;
  soupWidget();
  echo $after_widget;
}

function soupWidget_init()
{
  register_sidebar_widget(__('Soup.io Feed'), 'widget_soupFeed');
  register_widget_control(   'Soup.io Feed', 'soupFeed_control', 200, 200 );
}
add_action("plugins_loaded", "soupWidget_init");

function soupFeed_control()
{
	$options = get_option("widget_soupFeed");
	if (!is_array( $options))
	{
		$options = array(
		'soup-userid' => ''
		); 
	}

	if ($_POST['soupFeed-Submit'])
	{
	$options['soup-userid'] = htmlspecialchars($_POST['soupFeed-userid']);
	update_option("widget_soupFeed", $options);
	}
?>
<p>
  <label for="soupFeed-Userid">Soup.io User Id: </label>
  <input type="text" id="soupFeed-userid" name="soupFeed-userid" value="<?php echo $options['soup-userid'];?>" size="15" /><br />
  <input type="hidden" id="soupFeed-Submit"  name="soupFeed-Submit" value="1" />
</p>
<?php
}
?>
