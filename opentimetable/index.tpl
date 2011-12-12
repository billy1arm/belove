<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$Site_title}</title>

<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="js/jquery.tmpl.min.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script id="countryTemplate" type="text/x-jQuery-tmpl" >
{literal}
	<div  id='country_select' style='background-image:url(images/flags/${url_name}.png)' onclick=''>
		${url_name}
	</div> 
{/literal}
</script> 
<script id="cityTemplate" type="text/x-jQuery-tmpl" >
{literal}
	<div  id='city_select'  onclick=''>
		${url_name}
	</div> 
{/literal}
</script>
<script id="org_typeTemplate" type="text/x-jQuery-tmpl" >
{literal}
<div id='org_type_select' style='background-image:url(images/img2/${url_name}.png)' onclick=''>
		${url_name}
	</div> 
{/literal}
</script>
<script id="timeTemplate" type="text/x-jQuery-tmpl" >
{literal}
<div class='time'   onclick=''>
		${Day} ${Time}
	</div> 
{/literal}
</script>
</head>

<body>
<div id="container">
	<header>
    	<div id="date_time">
        	{$date_time}
        </div>
   	</header>
    <div  class="nav_panel">
    	<li><a href="#">{$Home}</a></li>
        <li><a href="#">{$About}</a></li>
        <li><a href="#">{$Contacts}</a></li>
    </div>
  	<div id="content">    
    	<div id="main_menu">
                <div class="menu_item" id="country">
                    <div class="text">
                        {$Country}
                    </div>
                    <div class="selected_text"></div> 
          </div>
            	<div class="menu_item" id="city">
                    <div class="text">
                        {$City}
                    </div> 
					<div class="selected_text"></div> 
                </div>
                <div class="fltlft selected" id="switch_item" onclick="" >
				<div class="text organization">
					{$Organizations}
				</div>
			</div>
			<div class="fltrt study" id="switch_item" onclick="">
				<div class="text">
					{$Study}
				</div>
			</div>  
            	<div class="menu_item" id="org_type">
                    <div class="text">
                        {$Category}
                    </div> 
                    <div class="selected_text"></div> 
                </div>
            	<div class="menu_item" id="org_name">
                    <div class="text">
                        {$Org_name}
                    </div> 
                    <div class="selected_text"></div> 
                </div>
			
			<div id="add_item">
				<div class="text">
					{$Add_timetable}
				</div> 
			</div>
		</div>
	</div>
 
 	<div id="select_menu">
 		<div id="turnback" ><</div>
	</div>
    
    <div id="timetable">
    	<div id="turnback" ><</div>
   	  
   	  <div  class="time">
      	Monday	
      </div>
      <div class="time">
      	Tuesday
      </div>
      <div class="time">
      	Wednesday
      </div>
      <div class="time">
      	Thursday
      </div>
      <div class="time">
      	Friday
      </div>
      <div class="time">
      	Saturday
      </div>
      <div class="time">
      	Sunday
      </div>
    </div>
 
	<div id="about_container">
	  <div id="turnback" ><</div>
		<div class="about kamilll">
			<div class="fold">Lebedev Ivan</div>
		</div>	
		<div class="about rainbow_ghost">
			<div class="fold">Blagodarnaya Irina</div>
		</div>
		<div class="about belove">
			<div class="fold">Belov Sergey</div>
		</div>
		<div class="about zolotyx">
			<div class="fold">Zolotukhin Ilya</div>
		</div>
	</div>
	<div id="footer">
	</div>
</div>
 

</body>
</html>
