	var winH;
	var winW;
	var winUrl;
	var winProp;
	var scrol;


function popUpOpen (winUrl, winW, winH)
{
//функция открывает заданный файл в заданных размерах окна
		scrol = 1;
		winProp = "toolbar=0,resizable=0,fullscreen=0,scrollbars="+scrol+",left=50,top=50,height="+winH+",width="+winW;
		window.open(winUrl,'',winProp);
}
//=======================

function popUpOpenNS (winUrl, winW, winH)
{
//функция открывает заданный файл в заданных размерах окна без скроллинга
		scrol = 0;
		winProp = "toolbar=0,resizable=0,fullscreen=0,scrollbars="+scrol+",left=50,top=50,height="+winH+",width="+winW;
		window.open(winUrl,'',winProp);
}
//=======================

function popUpOpenRS (winUrl, winW, winH)
{
//функция открывает заданный файл в окне с определёнными размерами с возможностью ресайза.
//скроллинг разрешён.
		scrol = 1;
		winProp = "width="+winW+",height="+winH+",scrollbars="+scrol+",left=50,top=50, toolbar=no,location=no,status=no,resizable=yes,screenX=50,screenY=50";
		window.open(winUrl,'',winProp);
}

//=======================
function popUpOpenARS (winUrl)
{
//функция открывает заданный файл в окне с неопределёнными параметрами с возможностью ресайза.
//скроллинг разрешён.
		winProp = "width=300,height=200,scrollbars=no,left=50,top=50, toolbar=no,location=no,status=no,resizable=yes,screenX=50,screenY=50";
		window.open(winUrl,'',winProp);
}
//=======================
