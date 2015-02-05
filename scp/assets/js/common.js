
/* Sticky Navigation Menu */
$(window).load(function(){
  $("header").sticky({ topSpacing: 0 });
});

/* One Page Scrolling */
$(document).ready(function() {
	$('header nav ul').onePageNav({
begin: function() {
		console.log('start');
		},
		end: function() {
		console.log('stop');
		},
	scrollOffset: 30
	});
});


/* Isotope Portfolio */
$(window).load(function() {

	var $container = $('#portfolio-container');

	$container.isotope({
		itemSelector: '.item',
		layoutMode: 'masonry'
	});

	var $optionSets = $('.option-set'),
			$optionLinks = $optionSets.find('li');

	$optionLinks.click(function() {
		var $this = $(this);
		// don't proceed if already selected
		if ($this.hasClass('selected')) {
			return false;
		}
		var $optionSet = $this.parents('.option-set');
		$optionSet.find('.selected').removeClass('selected');
		$this.addClass('selected');
	});

	$('#filters').on('click', 'a', function() {
		var selector = $(this).data('filter');
		$container.isotope({filter: selector});

	});

});



/* Custom Select Menu in Contact Form */
$( function() {
	
	$( '#cd-dropdown' ).dropdown( {
		gutter : 0
	} );

});

/* Responsive Menu */
$(function() {
	var pull 		= $('.pull');
		menu 		= $('nav ul');
		menuHeight	= menu.height();

	$(pull).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});

	$(window).resize(function(){
		var w = $(window).width();
		if(w > 320 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});
});

/* Testimonials Rotator */
	$(document).ready(function() {
		$('.testimonials ul li').quovolver();
	});
	
	// If JavaScript is enabled remove 'no-js' class and give 'js' class
jQuery('html').removeClass('no-js').addClass('js');

// When DOM is fully loaded
jQuery(document).ready(function($) {



//Readmore
$(document).ready(function() {
$(".readmore").hover(function(){
$(this).css("cursor","pointer"); 
},function(){
$(this).css("cursor","default");
});
$(".moreread").css("display","none");
$(".readmore").click(function(){
$(this).next().slideToggle("slow");
//$(this).next().css("display","block");
});
});
		


// UTF-8
/**
 * scrollsmoothly.js
 * Copyright (c) 2008 KAZUMiX
 * http://d.hatena.ne.jp/KAZUMiX/20080418/scrollsmoothly
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * 更新履歴
 * 2009/02/12
 * スクロール先が画面左上にならない場合の挙動を修正
 * 2008/04/18
 * 公開
 *
*/

(function(){
   var easing = 0.25;
   var interval = 20;
   var d = document;
   var targetX = 0;
   var targetY = 0;
   var targetHash = '';
   var scrolling = false;
   var splitHref = location.href.split('#');
   var currentHref_WOHash = splitHref[0];
   var incomingHash = splitHref[1];
   var prevX = null;
   var prevY = null;

   // ドキュメント読み込み完了時にinit()を実行する
   addEvent(window, 'load', init);

   // ドキュメント読み込み完了時の処理
   function init(){
     // ページ内リンクにイベントを設定する
     setOnClickHandler();
     // 外部からページ内リンク付きで呼び出された場合
     if(incomingHash){
       if(window.attachEvent && !window.opera){
         // IEの場合はちょっと待ってからスクロール
         setTimeout(function(){scrollTo(0,0);setScroll('#'+incomingHash);},50);
       }else{
         // IE以外はそのままGO
         scrollTo(0, 0);
         setScroll('#'+incomingHash);
       }
     }
   }

   // イベントを追加する関数
   function addEvent(eventTarget, eventName, func){
     if(eventTarget.addEventListener){
       // モダンブラウザ
       eventTarget.addEventListener(eventName, func, false);
     }else if(window.attachEvent){
       // IE
       eventTarget.attachEvent('on'+eventName, function(){func.apply(eventTarget);});
     }
   }
   
   function setOnClickHandler(){
     var links = d.links;
     for(var i=0; i<links.length; i++){
       // ページ内リンクならスクロールさせる
       var link = links[i];
	  if(link.className != "scroll"){
    continue;
}
       var splitLinkHref = link.href.split('#');
       if(currentHref_WOHash == splitLinkHref[0] && d.getElementById(splitLinkHref[1])){
         addEvent(link, 'click', startScroll);
       }
     }
   }

   function startScroll(event){
     // リンクのデフォルト動作を殺す
     if(event){ // モダンブラウザ
       event.preventDefault();
       //alert('modern');
     }else if(window.event){ // IE
       window.event.returnValue = false;
       //alert('ie');
     }
     // thisは呼び出し元になってる
     setScroll(this.hash);
   }

   function setScroll(hash){
     // ハッシュからターゲット要素の座標をゲットする
     var targetEle = d.getElementById(hash.substr(1));
     if(!targetEle)return;
     //alert(scrollSize.height);
     // スクロール先座標をセットする
     var ele = targetEle;
     var x = 0;
     var y = 0;
     while(ele){
       x += ele.offsetLeft;
       y += ele.offsetTop;
       ele = ele.offsetParent;
     }
     var maxScroll = getScrollMaxXY();
     targetX = Math.min(x, maxScroll.x);
     targetY = Math.min(y, maxScroll.y);
     targetHash = hash;
     // スクロール停止中ならスクロール開始
     if(!scrolling){
       scrolling = true;
       scroll();
     }
   }

   function scroll(){
     var currentX = d.documentElement.scrollLeft||d.body.scrollLeft;
     var currentY = d.documentElement.scrollTop||d.body.scrollTop;
     var vx = (targetX - currentX) * easing;
     var vy = (targetY - currentY) * easing;
     var nextX = currentX + vx;
     var nextY = currentY + vy;
     if((Math.abs(vx) < 1 && Math.abs(vy) < 1)
       || (prevX === currentX && prevY === currentY)){
       // 目標座標付近に到達していたら終了
       scrollTo(targetX, targetY);
       scrolling = false;
       location.hash = targetHash;
       prevX = prevY = null;
       return;
     }else{
       // 繰り返し
       scrollTo(parseInt(nextX), parseInt(nextY));
       prevX = currentX;
       prevY = currentY;
       setTimeout(function(){scroll()},interval);
     }
   }
   
   function getDocumentSize(){
     return {width:Math.max(document.body.scrollWidth, document.documentElement.scrollWidth), height:Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)};
   }

   function getWindowSize(){
     var result = {};
     if(window.innerWidth){
       var box = d.createElement('div');
       with(box.style){
         position = 'absolute';
         top = '0px';
         left = '0px';
         width = '100%';
         height = '100%';
         margin = '0px';
         padding = '0px';
         border = 'none';
         visibility = 'hidden';
       }
       d.body.appendChild(box);
       var width = box.offsetWidth;
       var height = box.offsetHeight;
       d.body.removeChild(box);
       result = {width:width, height:height};
     }else{
       result = {width:d.documentElement.clientWidth || d.body.clientWidth, height:d.documentElement.clientHeight || d.body.clientHeight};
     }
     return result;
   }
   
   function getScrollMaxXY() {
     if(window.scrollMaxX && window.scrollMaxY){
       return {x:window.scrollMaxX, y:window.scrollMaxY};
     }
     var documentSize = getDocumentSize();
     var windowSize = getWindowSize();
     return {x:documentSize.width - windowSize.width, y:documentSize.height - windowSize.height};
   }
   
 }());

/*
  スクロールすると出てくる「ページトップへ」ボタン
  http://another-step.com/2010/08/14/29/
*/

	
var scrolltotop={

	//startline: Integer. Number of pixels from top of doc scrollbar is scrolled before showing control
	setting: {startline:300},

	state: {isvisible:false, shouldvisible:false},

	togglecontrol:function(){

		var scrolltop=jQuery(window).scrollTop()

		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false
		if (this.state.shouldvisible && !this.state.isvisible){
			jQuery(".pageTop").fadeIn();
			this.state.isvisible=true;

		}
		else if (this.state.shouldvisible==false && this.state.isvisible){
			jQuery(".pageTop").fadeOut();
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			jQuery(".pageTop").hide();
			var mainobj=scrolltotop;

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			})
		})
	}
}
scrolltotop.init();

	

	
	

/* ---------------------------------------------------------------------- */
	/*	Min-height
	/* ---------------------------------------------------------------------- */

	(function() {

		// Set minimum height so footer will stay at the bottom of the window, even if there isn't enough content
		function setMinHeight() {

			$('#content').css('min-height',
				$(window).outerHeight(true)
				- ( $('body').outerHeight(true)
				- $('body').height() )
				- $('#header').outerHeight(true)
				- ( $('#content').outerHeight(true) - $('#content').height() )
				+ ( $('.page-title').length ? Math.abs( parseInt( $('.page-title').css('margin-top') ) ) : 0 )
				- $('#footer').outerHeight(true)
				- $('#footer-bottom').outerHeight(true)
			);
		
		}

		// Init
		setMinHeight();

		// Window resize
		$(window).on('resize', function() {

			var timer = window.setTimeout( function() {
				window.clearTimeout( timer );
				setMinHeight();
			}, 30 );

		});

	})();

	/* end Min-height */


	

	/* ---------------------------------------------------- */
	/*	Content Tabs
	/* ---------------------------------------------------- */

	(function() {

		var $tabsNav    = $('.tabs-nav'),
			$tabsNavLis = $tabsNav.children('li'),
			$tabContent = $('.tab-content');

		$tabsNav.each(function() {
			var $this = $(this);

			$this.next().children('.tab-content').stop(true,true).hide()
												 .first().show();

			$this.children('li').first().addClass('active').stop(true,true).show();
		});

		$tabsNavLis.on('click', function(e) {
			var $this = $(this);

			$this.siblings().removeClass('active').end()
				 .addClass('active');
			
			$this.parent().next().children('.tab-content').stop(true,true).hide()
														  .siblings( $this.find('a').attr('href') ).fadeIn();

			e.preventDefault();
		});

	})();

	/* end Content Tabs */
	

	
	
	/*---------------------------------
		CSS Helpers
	-----------------------------------*/
	if($.browser.msie){ $('body').addClass('msie'); }
	$('input[type=checkbox]').addClass('checkbox');
	$('input[type=radio]').addClass('radio');
	$('input[type=file]').addClass('file');
	$('[disabled=disabled]').addClass('disabled');
	$('table').find('tr:even').addClass('alt');
	$('table').find('tr:first-child').addClass('first');
	$('table').find('tr:last-child').addClass('last');
	$('ul').find('li:first-child').addClass('first');
	$('ul').find('li:last-child').addClass('last');
	$('hr').before('<div class="clear">ﾂ</div>');
	
	$('#content a').addClass('textlink').each(
	    function (i,o){
	      var div = document.createElement('span'); 
	      while (o.firstChild) div.appendChild(o.firstChild);
	      o.appendChild(div);
	    }
	  );
	$('.zoom .content-wrp').prepend('<div class="zoom"></div>');
	$('.video .content-wrp').prepend('<div class="zoom video"></div>');
	$('ol').find('li').addClass('alt');
	$('[class*=col_]').not('input, label, dl').addClass('column').each(
	    function (i,o){
	      var div = document.createElement('div'); div.className="inner";
	      while (o.firstChild) div.appendChild(o.firstChild);
	      o.appendChild(div);
	    }
	  );
	
});


//オーバーレイ
$(document).ready(function() 
{
    //img overlays
    $('.container .item').on('mouseover', function()
    {
        var overlay = $(this).find('.overlay-wrp');
        var content = $(this).find('.overlay-content');
        var top = parseInt(overlay.height() * 0.5 - 4);

        overlay.stop(true,true).fadeIn(300);
        content.stop().animate({'top': top}, 400);
        
    }).on('mouseleave', function()
    {
        var overlay = $(this).find('.overlay-wrp');
        var content = $(this).find('.overlay-content');
        var top = parseInt(overlay.height() * 0.2);
        
        content.stop().animate({'top': top}, 100);
        overlay.fadeOut(200);
    });
});

(function($) {

	$(function() {
		$.yuga.selflink();
		//$.yuga.rollover();
		$.yuga.externalLink();
		//$.yuga.thickbox();
		//$.yuga.scroll();
		//$.yuga.tab();
		$.yuga.stripe();
		//$.yuga.css3class();
	});
	//---------------------------------------------------------------------

	$.yuga = {
		Uri: function(path){
			var self = this;
			this.originalPath = path;
			this.absolutePath = (function(){
				var e = document.createElement('span');
				e.innerHTML = '<a href="' + path + '" />';
				return e.firstChild.href;
			})();
			var fields = {'schema' : 2, 'username' : 5, 'password' : 6, 'host' : 7, 'path' : 9, 'query' : 10, 'fragment' : 11};
			var r = /^((\w+):)?(\/\/)?((\w+):?(\w+)?@)?([^\/\?:]+):?(\d+)?(\/?[^\?#]+)?\??([^#]+)?#?(\w*)/.exec(this.absolutePath);
			for (var field in fields) {
				this[field] = r[fields[field]];
			}
			this.querys = {};
			if(this.query){
				$.each(self.query.split('&'), function(){
					var a = this.split('=');
					if (a.length == 2) self.querys[a[0]] = a[1];
				});
			}
		},
		selflink: function (options) {
			var c = $.extend({
				selfLinkAreaSelector:'body',
				selfLinkClass:'current',
				parentsLinkClass:'parentsLink',
				postfix: '_cr',
				changeImgSelf:true,
				changeImgParents:true
			}, options);
			$(c.selfLinkAreaSelector+((c.selfLinkAreaSelector)?' ':'')+'a[href]').each(function(){
				var href = new $.yuga.Uri(this.getAttribute('href'));
				var setImgFlg = false;
				if ((href.absolutePath == location.href) && !href.fragment) {
					//同じ文書にリンク
					$(this).addClass(c.selfLinkClass);
					setImgFlg = c.changeImgSelf;
				} else if (0 <= location.href.search(href.absolutePath)) {
					//親ディレクトリリンク
					$(this).addClass(c.parentsLinkClass);
					setImgFlg = c.changeImgParents;
				}
				if (setImgFlg){
					//img要素が含まれていたら現在用画像（_cr）に設定
				//	$(this).find('img').each(function(){
					//	this.originalSrc = $(this).attr('src');
					//	this.currentSrc = this.originalSrc.replace(new RegExp('('+c.postfix+')?(\.gif|\.jpg|\.png)$'), c.postfix+"$2");
					//	$(this).attr('src',this.currentSrc);
				//	});
				}
			});
		},
		rollover: function(options) {
			var c = $.extend({
				hoverSelector: '.btn, .allbtn img',
				groupSelector: '.btngroup',
				postfix: '_on'
			}, options);
			var rolloverImgs = $(c.hoverSelector).filter(isNotCurrent);
			rolloverImgs.each(function(){
				this.originalSrc = $(this).attr('src');
				this.rolloverSrc = this.originalSrc.replace(new RegExp('('+c.postfix+')?(\.gif|\.jpg|\.png)$'), c.postfix+"$2");
				this.rolloverImg = new Image;
				this.rolloverImg.src = this.rolloverSrc;
			});
			var groupingImgs = $(c.groupSelector).find('img').filter(isRolloverImg);

			rolloverImgs.not(groupingImgs).hover(function(){
				$(this).attr('src',this.rolloverSrc);
			},function(){
				$(this).attr('src',this.originalSrc);
			});
			$(c.groupSelector).hover(function(){
				$(this).find('img').filter(isRolloverImg).each(function(){
					$(this).attr('src',this.rolloverSrc);
				});
			},function(){
				$(this).find('img').filter(isRolloverImg).each(function(){
					$(this).attr('src',this.originalSrc);
				});
			});
			function isNotCurrent(i){
				return Boolean(!this.currentSrc);
			}
			function isRolloverImg(i){
				return Boolean(this.rolloverSrc);
			}

		},
		externalLink: function(options) {
			var c = $.extend({
				windowOpen:true,
				externalClass: 'externalLink',
				notWindowURL: '',
				pdfIconSrc: '/img/ico/ico_pdf.gif',
				xlsIconSrc: '/img/ico/ico_xls.gif',
				docIconSrc: '/img/ico/ico_doc.gif',
				zipIconSrc: '/img/ico/ico_zip.gif',
				pptIconSrc: '/img/ico/ico_ppt.gif',
				addIconSrc: '/img/ico/ico_blank.gif'
			}, options);
			var uri = new $.yuga.Uri(location.href);
			var e = $('a[href^="http://"], a[href^="https://"], a.exLink, a[href$=".pdf"]').not('a[href^="http://www.youtube.com/"],a[href^="https://www.youtube.com/"],a[href^="' + uri.schema + '://' + uri.host + '/' + '"]').not(c.notWindowURL);
			if (c.windowOpen) {
				e.click(function(){
					window.open(this.href, '_blank');
					return false;
				});
			}
			if (c.addIconSrc) e.not(':has(img), :has(button), .twitter-share-button, a[href$=".pdf"], .iframe a').append($('<img src="'+c.addIconSrc+'" class="externalIcon" />'));
			e.addClass(c.externalClass);
			$('a[href$=".pdf"]').not(':has(img)').prepend($('<img src="'+c.pdfIconSrc+'" class="pdfIcon" alt="PDF" />'));
			$('a[href$=".xls"]').not(':has(img)').prepend($('<img src="'+c.xlsIconSrc+'" class="xlsIcon" alt="EXEL" />'));
			$('a[href$=".xlsx"]').not(':has(img)').prepend($('<img src="'+c.xlsIconSrc+'" class="xlsIcon" alt="EXEL" />'));
			$('a[href$=".doc"]').not(':has(img)').prepend($('<img src="'+c.docIconSrc+'" class="docIcon" alt="WORD" />'));
			$('a[href$=".docx"]').not(':has(img)').prepend($('<img src="'+c.docIconSrc+'" class="docIcon" alt="WORD" />'));
			$('a[href$=".zip"]').not(':has(img)').prepend($('<img src="'+c.zipIconSrc+'" class="zipIcon" alt="ZIP" />'));
			$('a[href$=".ppt"]').not(':has(img)').prepend($('<img src="'+c.pptIconSrc+'" class="pptIcon" alt="PPT" />'));
			$('a[href$=".pptx"]').not(':has(img)').prepend($('<img src="'+c.pptIconSrc+'" class="pptIcon" alt="PPT" />'));
		},
		
	
	
		stripe: function(options) {
			var c = $.extend({
				oddClass:'odd',
				evenClass:'even'
			}, options);
			$('ul.striped').each(function(){
				$(this).children('li:odd').addClass(c.evenClass);
				$(this).children('li:even').addClass(c.oddClass);
			});
		}
	};
})(jQuery);


//IEで点線を消す
$(document).ready(function(){$("a").bind("focus",function(){if(this.blur)this.blur();});});



/*--------------------------------------------------------------------------*
 *  
 *  heightLine JavaScript Library beta4
 *  
 *  MIT-style license. 
 *  
 *  2007 Kazuma Nishihata 
 *  http://www.webcreativepark.net
 *  
 *--------------------------------------------------------------------------*/
new function(){
	
	function heightLine(){
	
		this.className="heightLine";
		this.parentClassName="heightLineParent"
		reg = new RegExp(this.className+"-([a-zA-Z0-9-_]+)", "i");
		objCN =new Array();
		var objAll = document.getElementsByTagName ? document.getElementsByTagName("*") : document.all;
		for(var i = 0; i < objAll.length; i++) {
			var eltClass = objAll[i].className.split(/\s+/);
			for(var j = 0; j < eltClass.length; j++) {
				if(eltClass[j] == this.className) {
					if(!objCN["main CN"]) objCN["main CN"] = new Array();
					objCN["main CN"].push(objAll[i]);
					break;
				}else if(eltClass[j] == this.parentClassName){
					if(!objCN["parent CN"]) objCN["parent CN"] = new Array();
					objCN["parent CN"].push(objAll[i]);
					break;
				}else if(eltClass[j].match(reg)){
					var OCN = eltClass[j].match(reg)
					if(!objCN[OCN]) objCN[OCN]=new Array();
					objCN[OCN].push(objAll[i]);
					break;
				}
			}
		}
		
		//check font size
		var e = document.createElement("div");
		var s = document.createTextNode("S");
		e.appendChild(s);
		e.style.visibility="hidden"
		e.style.position="absolute"
		e.style.top="0"
		document.body.appendChild(e);
		var defHeight = e.offsetHeight;
		
		changeBoxSize = function(){
			for(var key in objCN){
				if (objCN.hasOwnProperty(key)) {
					//parent type
					if(key == "parent CN"){
						for(var i=0 ; i<objCN[key].length ; i++){
							var max_height=0;
							var CCN = objCN[key][i].childNodes;
							for(var j=0 ; j<CCN.length ; j++){
								if(CCN[j] && CCN[j].nodeType == 1){
									CCN[j].style.height="auto";
									max_height = max_height>CCN[j].offsetHeight?max_height:CCN[j].offsetHeight;
								}
							}
							for(var j=0 ; j<CCN.length ; j++){
								if(CCN[j].style){
									var stylea = CCN[j].currentStyle || document.defaultView.getComputedStyle(CCN[j], '');
									var newheight = max_height;
									if(stylea.paddingTop)newheight -= stylea.paddingTop.replace("px","");
									if(stylea.paddingBottom)newheight -= stylea.paddingBottom.replace("px","");
									if(stylea.borderTopWidth && stylea.borderTopWidth != "medium")newheight-= stylea.borderTopWidth.replace("px","");
									if(stylea.borderBottomWidth && stylea.borderBottomWidth != "medium")newheight-= stylea.borderBottomWidth.replace("px","");
									CCN[j].style.height =newheight+"px";
								}
							}
						}
					}else{
						var max_height=0;
						for(var i=0 ; i<objCN[key].length ; i++){
							objCN[key][i].style.height="auto";
							max_height = max_height>objCN[key][i].offsetHeight?max_height:objCN[key][i].offsetHeight;
						}
						for(var i=0 ; i<objCN[key].length ; i++){
							if(objCN[key][i].style){
								var stylea = objCN[key][i].currentStyle || document.defaultView.getComputedStyle(objCN[key][i], '');
									var newheight = max_height;
									if(stylea.paddingTop)newheight-= stylea.paddingTop.replace("px","");
									if(stylea.paddingBottom)newheight-= stylea.paddingBottom.replace("px","");
									if(stylea.borderTopWidth && stylea.borderTopWidth != "medium")newheight-= stylea.borderTopWidth.replace("px","")
									if(stylea.borderBottomWidth && stylea.borderBottomWidth != "medium")newheight-= stylea.borderBottomWidth.replace("px","");
									objCN[key][i].style.height =newheight+"px";
							}
						}
					}
				}
			}
		}
		
		checkBoxSize = function(){
			if(defHeight != e.offsetHeight){
				changeBoxSize();
				defHeight= e.offsetHeight;
			}
		}
		changeBoxSize();
		setInterval(checkBoxSize,1000)
		window.onresize=changeBoxSize;
	}
	
	function addEvent(elm,listener,fn){
		try{
			elm.addEventListener(listener,fn,false);
		}catch(e){
			elm.attachEvent("on"+listener,fn);
		}
	}
	addEvent(window,"load",heightLine);
}

	$(function(){
			$('.post').show();
			var $container = $('#posts');
			$container.isotope({
				itemSelector : '.post'
			});
			var $optionSets = $('.option-set'),
				$optionLinks = $optionSets.find('a');
			$optionLinks.click(function(){
				var $this = $(this);
				// don't proceed if already selected
				if ( $this.hasClass('selected') ) {
					return false;
				}
				var $optionSet = $this.parents('.option-set');
				$optionSet.find('.selected').removeClass('selected');
				$this.addClass('selected');
			// make option object dynamically, i.e. { filter: '.my-filter-class' }
			var options = {},
				key = $optionSet.attr('data-option-key'),
				value = $this.attr('data-option-value');
				
			// parse 'false' as false boolean
			value = value === 'false' ? false : value;
			options[ key ] = value;
				if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
				// changes in layout modes need extra logic
				changeLayoutMode( $this, options )
			} else {
				// otherwise, apply new options
				$container.isotope( options );
			}    
			return false;
            
			});
         $('#posts').isotope({
  masonry: {
    columnWidth: 1
  }
            });
		});


 
   function moveTo(contentArea){
        	var goPosition = $(contentArea).offset().top;
        	$('html,body').animate({ scrollTop: goPosition}, 'slow');
    }
		
	
// HTML5 Shiv v3 | @jon_neal @afarkas @rem | MIT/GPL2 Licensed
// Uncompressed source: https://github.com/aFarkas/html5shiv
(function(a,b){function f(a){var c,d,e,f;b.documentMode>7?(c=b.createElement("font"),c.setAttribute("data-html5shiv",a.nodeName.toLowerCase())):c=b.createElement("shiv:"+a.nodeName);while(a.firstChild)c.appendChild(a.childNodes[0]);for(d=a.attributes,e=d.length,f=0;f<e;++f)d[f].specified&&c.setAttribute(d[f].nodeName,d[f].nodeValue);c.style.cssText=a.style.cssText,a.parentNode.replaceChild(c,a),c.originalElement=a}function g(a){var b=a.originalElement;while(a.childNodes.length)b.appendChild(a.childNodes[0]);a.parentNode.replaceChild(b,a)}function h(a,b){b=b||"all";var c=-1,d=[],e=a.length,f,g;while(++c<e){f=a[c],g=f.media||b;if(f.disabled||!/print|all/.test(g))continue;d.push(h(f.imports,g),f.cssText)}return d.join("")}function i(c){var d=new RegExp("(^|[\\s,{}])("+a.html5.elements.join("|")+")","gi"),e=c.split("{"),f=e.length,g=-1;while(++g<f)e[g]=e[g].split("}"),b.documentMode>7?e[g][e[g].length-1]=e[g][e[g].length-1].replace(d,'$1font[data-html5shiv="$2"]'):e[g][e[g].length-1]=e[g][e[g].length-1].replace(d,"$1shiv\\:$2"),e[g]=e[g].join("}");return e.join("{")}var c=function(a){return a.innerHTML="<x-element></x-element>",a.childNodes.length===1}(b.createElement("a")),d=function(a,b,c){return b.appendChild(a),(c=(c?c(a):a.currentStyle).display)&&b.removeChild(a)&&c==="block"}(b.createElement("nav"),b.documentElement,a.getComputedStyle),e={elements:"abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video".split(" "),shivDocument:function(a){a=a||b;if(a.documentShived)return;a.documentShived=!0;var f=a.createElement,g=a.createDocumentFragment,h=a.getElementsByTagName("head")[0],i=function(a){f(a)};c||(e.elements.join(" ").replace(/\w+/g,i),a.createElement=function(a){var b=f(a);return b.canHaveChildren&&e.shivDocument(b.document),b},a.createDocumentFragment=function(){return e.shivDocument(g())});if(!d&&h){var j=f("div");j.innerHTML=["x<style>","article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}","audio{display:none}","canvas,video{display:inline-block;*display:inline;*zoom:1}","[hidden]{display:none}audio[controls]{display:inline-block;*display:inline;*zoom:1}","mark{background:#FF0;color:#000}","</style>"].join(""),h.insertBefore(j.lastChild,h.firstChild)}return a}};e.shivDocument(b),a.html5=e;if(c||!a.attachEvent)return;a.attachEvent("onbeforeprint",function(){if(a.html5.supportsXElement||!b.namespaces)return;b.namespaces.shiv||b.namespaces.add("shiv");var c=-1,d=new RegExp("^("+a.html5.elements.join("|")+")$","i"),e=b.getElementsByTagName("*"),g=e.length,j,k=i(h(function(a,b){var c=[],d=a.length;while(d)c.unshift(a[--d]);d=b.length;while(d)c.unshift(b[--d]);c.sort(function(a,b){return a.sourceIndex-b.sourceIndex}),d=c.length;while(d)c[--d]=c[d].styleSheet;return c}(b.getElementsByTagName("style"),b.getElementsByTagName("link"))));while(++c<g)j=e[c],d.test(j.nodeName)&&f(j);b.appendChild(b._shivedStyleSheet=b.createElement("style")).styleSheet.cssText=k}),a.attachEvent("onafterprint",function(){if(a.html5.supportsXElement||!b.namespaces)return;var c=-1,d=b.getElementsByTagName("*"),e=d.length,f;while(++c<e)f=d[c],f.originalElement&&g(f);b._shivedStyleSheet&&b._shivedStyleSheet.parentNode.removeChild(b._shivedStyleSheet)})});(this,document);

// Chosen, a Select Box Enhancer for jQuery and Protoype
// by Patrick Filler for Harvest, http://getharvest.com
// 
// Version 0.9.7
// Full source at https://github.com/harvesthq/chosen
// Copyright (c) 2011 Harvest http://getharvest.com

// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `cake build`, do not edit it by hand.
((function(){var a;a=function(){function a(){this.options_index=0,this.parsed=[]}return a.prototype.add_node=function(a){return a.nodeName==="OPTGROUP"?this.add_group(a):this.add_option(a)},a.prototype.add_group=function(a){var b,c,d,e,f,g;b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:a.label,children:0,disabled:a.disabled}),f=a.childNodes,g=[];for(d=0,e=f.length;d<e;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},a.prototype.add_option=function(a,b,c){if(a.nodeName==="OPTION")return a.text!==""?(b!=null&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1},a}(),a.select_to_array=function(b){var c,d,e,f,g;d=new a,g=b.childNodes;for(e=0,f=g.length;e<f;e++)c=g[e],d.add_node(c);return d.parsed},this.SelectParser=a})).call(this),function(){var a,b;b=this,a=function(){function a(a,b){this.form_field=a,this.options=b!=null?b:{},this.set_default_values(),this.is_multiple=this.form_field.multiple,this.default_text_default=this.is_multiple?"Select Some Options":"Select an Option",this.setup(),this.set_up_html(),this.register_observers(),this.finish_setup()}return a.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=this.options.allow_single_deselect!=null&&this.form_field.options[0]!=null&&this.form_field.options[0].text===""?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.choices=0,this.results_none_found=this.options.no_results_text||"No results match"},a.prototype.mouse_enter=function(){return this.mouse_on_container=!0},a.prototype.mouse_leave=function(){return this.mouse_on_container=!1},a.prototype.input_focus=function(a){var b=this;if(!this.active_field)return setTimeout(function(){return b.container_mousedown()},50)},a.prototype.input_blur=function(a){var b=this;if(!this.mouse_on_container)return this.active_field=!1,setTimeout(function(){return b.blur_test()},100)},a.prototype.result_add_option=function(a){var b,c;return a.disabled?"":(a.dom_id=this.container_id+"_o_"+a.array_index,b=a.selected&&this.is_multiple?[]:["active-result"],a.selected&&b.push("result-selected"),a.group_array_index!=null&&b.push("group-option"),a.classes!==""&&b.push(a.classes),c=a.style.cssText!==""?' style="'+a.style+'"':"",'<li id="'+a.dom_id+'" class="'+b.join(" ")+'"'+c+">"+a.html+"</li>")},a.prototype.results_update_field=function(){return this.result_clear_highlight(),this.result_single_selected=null,this.results_build()},a.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},a.prototype.results_search=function(a){return this.results_showing?this.winnow_results():this.results_show()},a.prototype.keyup_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale();switch(b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:a.preventDefault();if(this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},a.prototype.generate_field_id=function(){var a;return a=this.generate_random_id(),this.form_field.id=a,a},a.prototype.generate_random_char=function(){var a,b,c;return a="0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ",c=Math.floor(Math.random()*a.length),b=a.substring(c,c+1)},a}(),b.AbstractChosen=a}.call(this),function(){var a,b,c,d,e=Object.prototype.hasOwnProperty,f=function(a,b){function d(){this.constructor=a}for(var c in b)e.call(b,c)&&(a[c]=b[c]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};d=this,a=jQuery,a.fn.extend({chosen:function(c){return!a.browser.msie||a.browser.version!=="6.0"&&a.browser.version!=="7.0"?a(this).each(function(d){if(!a(this).hasClass("chzn-done"))return new b(this,c)}):this}}),b=function(b){function e(){e.__super__.constructor.apply(this,arguments)}return f(e,b),e.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.is_rtl=this.form_field_jq.hasClass("chzn-rtl")},e.prototype.finish_setup=function(){return this.form_field_jq.addClass("chzn-done")},e.prototype.set_up_html=function(){var b,d,e,f;return this.container_id=this.form_field.id.length?this.form_field.id.replace(/(:|\.)/g,"_"):this.generate_field_id(),this.container_id+="_chzn",this.f_width=this.form_field_jq.outerWidth(),this.default_text=this.form_field_jq.data("placeholder")?this.form_field_jq.data("placeholder"):this.default_text_default,b=a("<div />",{id:this.container_id,"class":"chzn-container"+(this.is_rtl?" chzn-rtl":""),style:"width: "+this.f_width+"px;"}),this.is_multiple?b.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>'):b.html('<a href="javascript:void(0)" class="chzn-single"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'),this.form_field_jq.hide().after(b),this.container=a("#"+this.container_id),this.container.addClass("chzn-container-"+(this.is_multiple?"multi":"single")),this.dropdown=this.container.find("div.chzn-drop").first(),d=this.container.height(),e=this.f_width-c(this.dropdown),this.dropdown.css({width:e+"px",top:d+"px"}),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chzn-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chzn-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chzn-search").first(),this.selected_item=this.container.find(".chzn-single").first(),f=e-c(this.search_container)-c(this.search_field),this.search_field.css({width:f+"px"})),this.results_build(),this.set_tab_index(),this.form_field_jq.trigger("liszt:ready",{chosen:this})},e.prototype.register_observers=function(){var a=this;return this.container.mousedown(function(b){return a.container_mousedown(b)}),this.container.mouseup(function(b){return a.container_mouseup(b)}),this.container.mouseenter(function(b){return a.mouse_enter(b)}),this.container.mouseleave(function(b){return a.mouse_leave(b)}),this.search_results.mouseup(function(b){return a.search_results_mouseup(b)}),this.search_results.mouseover(function(b){return a.search_results_mouseover(b)}),this.search_results.mouseout(function(b){return a.search_results_mouseout(b)}),this.form_field_jq.bind("liszt:updated",function(b){return a.results_update_field(b)}),this.search_field.blur(function(b){return a.input_blur(b)}),this.search_field.keyup(function(b){return a.keyup_checker(b)}),this.search_field.keydown(function(b){return a.keydown_checker(b)}),this.is_multiple?(this.search_choices.click(function(b){return a.choices_click(b)}),this.search_field.focus(function(b){return a.input_focus(b)})):this.container.click(function(a){return a.preventDefault()})},e.prototype.search_field_disabled=function(){this.is_disabled=this.form_field_jq[0].disabled;if(this.is_disabled)return this.container.addClass("chzn-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus",this.activate_action),this.close_field();this.container.removeClass("chzn-disabled"),this.search_field[0].disabled=!1;if(!this.is_multiple)return this.selected_item.bind("focus",this.activate_action)},e.prototype.container_mousedown=function(b){var c;if(!this.is_disabled)return c=b!=null?a(b.target).hasClass("search-choice-close"):!1,b&&b.type==="mousedown"&&b.stopPropagation(),!this.pending_destroy_click&&!c?(this.active_field?!this.is_multiple&&b&&(a(b.target)[0]===this.selected_item[0]||a(b.target).parents("a.chzn-single").length)&&(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(document).click(this.click_test_action),this.results_show()),this.activate_field()):this.pending_destroy_click=!1},e.prototype.container_mouseup=function(a){if(a.target.nodeName==="ABBR")return this.results_reset(a)},e.prototype.blur_test=function(a){if(!this.active_field&&this.container.hasClass("chzn-container-active"))return this.close_field()},e.prototype.close_field=function(){return a(document).unbind("click",this.click_test_action),this.is_multiple||(this.selected_item.attr("tabindex",this.search_field.attr("tabindex")),this.search_field.attr("tabindex",-1)),this.active_field=!1,this.results_hide(),this.container.removeClass("chzn-container-active"),this.winnow_results_clear(),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},e.prototype.activate_field=function(){return!this.is_multiple&&!this.active_field&&(this.search_field.attr("tabindex",this.selected_item.attr("tabindex")),this.selected_item.attr("tabindex",-1)),this.container.addClass("chzn-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},e.prototype.test_active_click=function(b){return a(b.target).parents("#"+this.container_id).length?this.active_field=!0:this.close_field()},e.prototype.results_build=function(){var a,b,c,e,f;this.parsing=!0,this.results_data=d.SelectParser.select_to_array(this.form_field),this.is_multiple&&this.choices>0?(this.search_choices.find("li.search-choice").remove(),this.choices=0):this.is_multiple||(this.selected_item.find("span").text(this.default_text),this.form_field.options.length<=this.disable_search_threshold?this.container.addClass("chzn-container-single-nosearch"):this.container.removeClass("chzn-container-single-nosearch")),a="",f=this.results_data;for(c=0,e=f.length;c<e;c++)b=f[c],b.group?a+=this.result_add_group(b):b.empty||(a+=this.result_add_option(b),b.selected&&this.is_multiple?this.choice_build(b):b.selected&&!this.is_multiple&&(this.selected_item.find("span").text(b.text),this.allow_single_deselect&&this.single_deselect_control_build()));return this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.search_results.html(a),this.parsing=!1},e.prototype.result_add_group=function(b){return b.disabled?"":(b.dom_id=this.container_id+"_g_"+b.array_index,'<li id="'+b.dom_id+'" class="group-result">'+a("<div />").text(b.label).html()+"</li>")},e.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight();if(b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(c<f)return this.search_results.scrollTop(c)}},e.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},e.prototype.results_show=function(){var a;return this.is_multiple||(this.selected_item.addClass("chzn-single-with-drop"),this.result_single_selected&&this.result_do_highlight(this.result_single_selected)),a=this.is_multiple?this.container.height():this.container.height()-1,this.dropdown.css({top:a+"px",left:0}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results()},e.prototype.results_hide=function(){return this.is_multiple||this.selected_item.removeClass("chzn-single-with-drop"),this.result_clear_highlight(),this.dropdown.css({left:"-9000px"}),this.results_showing=!1},e.prototype.set_tab_index=function(a){var b;if(this.form_field_jq.attr("tabindex"))return b=this.form_field_jq.attr("tabindex"),this.form_field_jq.attr("tabindex",-1),this.is_multiple?this.search_field.attr("tabindex",b):(this.selected_item.attr("tabindex",b),this.search_field.attr("tabindex",-1))},e.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},e.prototype.search_results_mouseup=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c.length)return this.result_highlight=c,this.result_select(b)},e.prototype.search_results_mouseover=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c)return this.result_do_highlight(c)},e.prototype.search_results_mouseout=function(b){if(a(b.target).hasClass("active-result"))return this.result_clear_highlight()},e.prototype.choices_click=function(b){b.preventDefault();if(this.active_field&&!a(b.target).hasClass("search-choice")&&!this.results_showing)return this.results_show()},e.prototype.choice_build=function(b){var c,d,e=this;return c=this.container_id+"_c_"+b.array_index,this.choices+=1,this.search_container.before('<li class="search-choice" id="'+c+'"><span>'+b.html+'</span><a href="javascript:void(0)" class="search-choice-close" rel="'+b.array_index+'"></a></li>'),d=a("#"+c).find("a").first(),d.click(function(a){return e.choice_destroy_link_click(a)})},e.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),this.is_disabled?b.stopPropagation:(this.pending_destroy_click=!0,this.choice_destroy(a(b.target)))},e.prototype.choice_destroy=function(a){return this.choices-=1,this.show_search_field_default(),this.is_multiple&&this.choices>0&&this.search_field.val().length<1&&this.results_hide(),this.result_deselect(a.attr("rel")),a.parents("li").first().remove()},e.prototype.results_reset=function(b){this.form_field.options[0].selected=!0,this.selected_item.find("span").text(this.default_text),this.show_search_field_default(),a(b.target).remove(),this.form_field_jq.trigger("change");if(this.active_field)return this.results_hide()},e.prototype.result_select=function(a){var b,c,d,e;if(this.result_highlight)return b=this.result_highlight,c=b.attr("id"),this.result_clear_highlight(),this.is_multiple?this.result_deactivate(b):(this.search_results.find(".result-selected").removeClass("result-selected"),this.result_single_selected=b),b.addClass("result-selected"),e=c.substr(c.lastIndexOf("_")+1),d=this.results_data[e],d.selected=!0,this.form_field.options[d.options_index].selected=!0,this.is_multiple?this.choice_build(d):(this.selected_item.find("span").first().text(d.text),this.allow_single_deselect&&this.single_deselect_control_build()),(!a.metaKey||!this.is_multiple)&&this.results_hide(),this.search_field.val(""),this.form_field_jq.trigger("change"),this.search_field_scale()},e.prototype.result_activate=function(a){return a.addClass("active-result")},e.prototype.result_deactivate=function(a){return a.removeClass("active-result")},e.prototype.result_deselect=function(b){var c,d;return d=this.results_data[b],d.selected=!1,this.form_field.options[d.options_index].selected=!1,c=a("#"+this.container_id+"_o_"+b),c.removeClass("result-selected").addClass("active-result").show(),this.result_clear_highlight(),this.winnow_results(),this.form_field_jq.trigger("change"),this.search_field_scale()},e.prototype.single_deselect_control_build=function(){if(this.allow_single_deselect&&this.selected_item.find("abbr").length<1)return this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')},e.prototype.winnow_results=function(){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r;this.no_results_clear(),i=0,j=this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html(),f=new RegExp("^"+j.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),m=new RegExp(j.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),r=this.results_data;for(n=0,p=r.length;n<p;n++){c=r[n];if(!c.disabled&&!c.empty)if(c.group)a("#"+c.dom_id).css("display","none");else if(!this.is_multiple||!c.selected){b=!1,h=c.dom_id,g=a("#"+h);if(f.test(c.html))b=!0,i+=1;else if(c.html.indexOf(" ")>=0||c.html.indexOf("[")===0){e=c.html.replace(/\[|\]/g,"").split(" ");if(e.length)for(o=0,q=e.length;o<q;o++)d=e[o],f.test(d)&&(b=!0,i+=1)}b?(j.length?(k=c.html.search(m),l=c.html.substr(0,k+j.length)+"</em>"+c.html.substr(k+j.length),l=l.substr(0,k)+"<em>"+l.substr(k)):l=c.html,g.html(l),this.result_activate(g),c.group_array_index!=null&&a("#"+this.results_data[c.group_array_index].dom_id).css("display","list-item")):(this.result_highlight&&h===this.result_highlight.attr("id")&&this.result_clear_highlight(),this.result_deactivate(g))}}return i<1&&j.length?this.no_results(j):this.winnow_results_set_highlight()},e.prototype.winnow_results_clear=function(){var b,c,d,e,f;this.search_field.val(""),c=this.search_results.find("li"),f=[];for(d=0,e=c.length;d<e;d++)b=c[d],b=a(b),b.hasClass("group-result")?f.push(b.css("display","auto")):!this.is_multiple||!b.hasClass("result-selected")?f.push(this.result_activate(b)):f.push(void 0);return f},e.prototype.winnow_results_set_highlight=function(){var a,b;if(!this.result_highlight){b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first();if(a!=null)return this.result_do_highlight(a)}},e.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c)},e.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},e.prototype.keydown_arrow=function(){var b,c;this.result_highlight?this.results_showing&&(c=this.result_highlight.nextAll("li.active-result").first(),c&&this.result_do_highlight(c)):(b=this.search_results.find("li.active-result").first(),b&&this.result_do_highlight(a(b)));if(!this.results_showing)return this.results_show()},e.prototype.keyup_arrow=function(){var a;if(!this.results_showing&&!this.is_multiple)return this.results_show();if(this.result_highlight)return a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices>0&&this.results_hide(),this.result_clear_highlight())},e.prototype.keydown_backstroke=function(){return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(this.pending_backstroke=this.search_container.siblings("li.search-choice").last(),this.pending_backstroke.addClass("search-choice-focus"))},e.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},e.prototype.keydown_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale(),b!==8&&this.pending_backstroke&&this.clear_backstroke();switch(b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:this.keydown_arrow()}},e.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"];for(i=0,j=g.length;i<j;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return c=a("<div />",{style:f}),c.text(this.search_field.val()),a("body").append(c),h=c.width()+25,c.remove(),h>this.f_width-10&&(h=this.f_width-10),this.search_field.css({width:h+"px"}),b=this.container.height(),this.dropdown.css({top:b+"px"})}},e.prototype.generate_random_id=function(){var b;b="sel"+this.generate_random_char()+this.generate_random_char()+this.generate_random_char();while(a("#"+b).length>0)b+=this.generate_random_char();return b},e}(AbstractChosen),c=function(a){var b;return b=a.outerWidth()-a.width()},d.get_side_border_padding=c}.call(this); 



