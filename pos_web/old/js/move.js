// JavaScript Document
function getStyle(obj, name)
{
	if(obj.currentStyle)
	{
		return obj.currentStyle[name];
	}
	else
	{
		return getComputedStyle(obj, false)[name];
	}
}

function stamove(obj, name, iTarget)
{
	clearInterval(obj.timer);
	obj.timer=setInterval(function (){
		if(name=='opacity')
		{
			var cur=Math.round(parseFloat(getStyle(obj, name))*100);
		}
		else
		{
			var cur=parseInt(getStyle(obj, name));
		}
		
		if(cur==iTarget)
		{
			clearInterval(obj.tiemr);
		}
		else
		{
			var speed=(iTarget-cur)/8;
			speed=speed>0?Math.ceil(speed):Math.floor(speed);
			
			if(name=='opacity')
			{
				obj.style.opacity=(cur+speed)/100;
				obj.style.filter='alpha(opacity:'+(cur+speed)+')';
			}
			else
			{
				obj.style[name]=cur+speed+'px';
			}
		}
	}, 30);
}