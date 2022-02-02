var hide = true;
$('#licon')
.css('cursor','pointer')
.click(
function()
{
	if(hide)
	{
		$('#links').css('display','block');
		$('#topnav').css('display','block');
		hide = false;
	}
	else
	{
		$('#links').css('display','none');
		hide = true;
	}
	
});

$(window).resize(function()
{
	if($(window).width() >= 1080)
	{
		$('#links').css('display','flex');
		$("#licon").css("display","none");
		hide = true;
	}
	else if($(window).width() < 1080 && hide == true)
	{
		$('#links').css('display','none');
		$("#licon").css("display","block");
	}
	if($(window).width() < 1080)
	{
		$(".navlink").css("max-height","300px");
	}
	else
	{
		$("#jewelery_nav").css("max-height","42px");
		$("#other_nav").css("max-height","42px");
	}
});

//////////////////////////////
var visible=false;
$("#jewelery_nav").click(
function()
{
	if(visible==false)
	{$(".jew_ul").css("display","block");
	visible=true;
	}
	else
	{
		$(".jew_ul").css("display","none");
		visible=false;
	}
});
var visible_2=false;
$("#other_nav").click(
function()
{
	if(visible_2==false)
	{$(".other_ul").css("display","block");
	visible_2=true;
	}
	else
	{
		$(".other_ul").css("display","none");
		visible_2=false;
	}
});

///////////////////////////
$("#logo").click(
	function(e)
	{
		if(e.target.className!="jew_ul" || e.target.className!="other_ul")
		{
			$(".jew_ul").css("display","none");
			visible=false;
			$(".other_ul").css("display","none");
			visible_2=false;
		}
	}
);
$("#container").click(
	function(e)
	{
		if(e.target.className!="jew_ul" || e.target.className!="other_ul")
		{
			$(".jew_ul").css("display","none");
			visible=false;
			$(".other_ul").css("display","none");
			visible_2=false;
		}
	}
);
$("#footer").click(
	function(e)
	{
		if(e.target.className!="jew_ul" || e.target.className!="other_ul")
		{
			$(".jew_ul").css("display","none");
			visible=false;
			$(".other_ul").css("display","none");
			visible_2=false;
		}
	}
);