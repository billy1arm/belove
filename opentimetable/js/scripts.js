//var d= new Date();
//var currentDay = d.getDay()-1;
//var month=new Array(12);
//month[0]="January";
//month[1]="February";
//month[2]="March";
//month[3]="April";
//month[4]="May";
//month[5]="June";
//month[6]="July";
//month[7]="August";
//month[8]="September";
//month[9]="October";
//month[10]="November";
//month[11]="December";


var timeTest = [
  { Day: "Monday", Time: "9:00 - 17:30"},
  { Day: "Tuesday", Time: "9:00 - 17:30"},
  { Day: "Wednesday", Time: "9:00 - 17:30"},
  { Day: "Thursday", Time: "9:00 - 17:30"},
  { Day: "Friday", Time: "9:00 - 17:30"},
  { Day: "Saturday", Time: "-"},
  { Day: "Sunday", Time: "-"},
  
];

function country_ajax()
	{
		
		$("div#city div.selected_text").text("");
		
		$.ajax({   
				type: 'POST',
                url: "get_json.php",
				data: { type: "country"},  
				dataType: "json",   
            	cache: false,   
            	success: function(json){ 
					$("#select_menu").html('<div id="turnback" ><</div>');
					$("#countryTemplate").tmpl(json).appendTo("#select_menu"); 
				}
				
		}); 
	}
	
	function city_ajax()	
	{
		if ($("div#country div.selected_text").text()!="")
		{
			$.ajax({   
					type: 'POST',
					url: "get_json.php",
					data: { type: "city", country: $("div#country div.selected_text").text()}, 
					dataType: "json",  
					cache: false,   
					success: function(json){
						$("#select_menu").html('<div id="turnback" ><</div>');
						$("#cityTemplate").tmpl(json).appendTo("#select_menu");   
					}
			});  
		}
	}

	function org_type_ajax()
	{
	$("div#org_name div.selected_text").text("");
		$.ajax({   
				type: 'POST',
                url: "get_json.php",
				data: { type: "org_type"},   
				dataType: "json",  
            	cache: false,   
            	success: function(json){   
                	$("#select_menu").html('<div id="turnback" ><</div>');
					$("#org_typeTemplate").tmpl(json).appendTo("#select_menu");    
				}
		});  
	}
	
	
		
	
	$(document).ready(function() {
	
		

		$("#country_select").live("click",function(){
			$("div#country div.selected_text").text($(this).text());
			$('html, body').animate({scrollTop: '0px'}, 800);
			$("div.field").fadeOut("fast");//, city_ajax);
			city_ajax();
			
			
		})
		//$("div.day").eq(currentDay).addClass("selected_day").css("margin-left", 0);
//		$(".time").css("top", currentDay*15 );
//		//alert(d.getDay());
//		$(".day").live("click",function(){
//			$("div.day").removeClass("selected_day").css("margin-left", 30);
//			$(this).addClass("selected_day").css("margin-left", 0);
//			$(".time").animate({top: $(this).position().top/2}, 400);
//		})
		
		$("#city_select").live("click",function(){
			$("div#city div.selected_text").text($(this).text());
			$('html, body').animate({scrollTop: '0px'}, 800);
			$("div.field").fadeOut("fast");
			org_type_ajax();
		})
		
		$("#org_type_select").live("click",function(){
			$("div#org_type div.selected_text").text($(this).text());
			$('html, body').animate({scrollTop: '0px'}, 800);
		})
		
		$("#country").click(function(){country_ajax()});
		
		$("#city").click(function(){
			if ($("#country div.selected_text").text()!="")
				city_ajax();
			else
				country_ajax();
		});
		
		$("#org_type").click(function(){org_type_ajax()});
		
		$(window).scroll(function () {
			if (($(window).scrollLeft()<3000)  && !($("#about_container").hasClass("opacity"))){
				$("#about_container").animate({opacity: 0.2}, 400).addClass("opacity");
			}
			else if  (($(window).scrollLeft()>=3000)  && ($("#about_container").hasClass("opacity")))
			{
				$("#about_container").animate({opacity: 1}, 400).removeClass("opacity");
			}
			if (($(this).scrollLeft()<500)  && !($("#select_menu").hasClass("opacity"))){
				$("#select_menu").animate({opacity: 0.2}, 400).addClass("opacity");
			}
			else if  (($(window).scrollLeft()>=500)  && ($("#select_menu").hasClass("opacity")))
			{
				$("#select_menu").animate({opacity: 1}, 400).removeClass("opacity");
			}
			if (($(this).scrollLeft()<2200)  && !($("#timetable").hasClass("opacity"))){
				$("#timetable").animate({opacity: 0.2}, 400).addClass("opacity");
			}
			else if  (($(window).scrollLeft()>=2200)  && ($("#timetable").hasClass("opacity")))
			{
				$("#timetable").animate({opacity: 1}, 400).removeClass("opacity");
			}
			if (($(window).scrollLeft()>=1200))
			{
					$("#date_time").css("left", $(window).scrollLeft()+200); 
			}
		});
	
		$("#add_item").click(function(){
				$("#timetable").html('<div id="turnback" ><</div>');
					$("#timeTemplate").tmpl(timeTest).appendTo("#timetable");
			})
		$("div#switch_item").live("click",function(){
			if (!$(this).hasClass("selected"))
			{
				$("#switch_item.selected").removeClass("selected");
				$(this).addClass("selected");
			}
		});   
		
		$(".nav_panel li:contains('About')").live('click',function(){
			$('html, body').animate({scrollLeft: $("#about_container").position().left-600}, 800);
		});
				
		$("div.menu_item").click(function(){
			if ($('html, body').scrollLeft()<900){
				$('html, body').animate({scrollLeft: 1100}, 800);
			}
		});
				
		$(".nav_panel li:contains('Home')").click(function(){
					$('html, body').animate({scrollLeft: $("#main_menu").position().left-200}, 800);
		});
		$("div#turnback").live('click',function(){
					$('html, body').animate({scrollLeft:  $("#main_menu").position().left-200}, 800);
		});
		
	});