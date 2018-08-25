(function () {
    if ( typeof NodeList.prototype.forEach === "function" ) return false;
    NodeList.prototype.forEach = Array.prototype.forEach;
})();

//infinite scroll
(function(e,t){"use strict";function i(t,i){this.element=t;this.options=e.extend({},r,i);this._defaults=r;this._name=n;this.loading=false;this.init()}var n="simpleInfiniteScroll";var r={offset:0,ajaxOptions:{},callback:null};i.prototype={init:function(){e(this.element).scroll(this.checkPage(this.element,this.options))},checkPage:function(t,n){return function(){var r=this;if(!r.loading){var i=e(t);if(i.scrollTop()>e(document).height()-i.height()-n.offset){r.loading=true;var s={success:function(e,t,i){if(typeof n.callback==="function"){n.callback.call(r,e,t,i)}r.loading=false}};e.extend(n.ajaxOptions,s);e.ajax(n.ajaxOptions)}}}}};e.fn[n]=function(t,r){return this.each(function(){if(!e.data(this,"plugin_"+n)){e.data(this,"plugin_"+n,new i(this,t))}})}})(jQuery)

/*! Lazy Load 2.0.0-beta.2 - MIT license - Copyright 2007-2017 Mika Tuupola */
!function(t,e){"object"==typeof exports?module.exports=e(t):"function"==typeof define&&define.amd?define([],e(t)):t.LazyLoad=e(t)}("undefined"!=typeof global?global:this.window||this.global,function(t){"use strict";function e(t,e){this.settings=r(s,e||{}),this.images=t||document.querySelectorAll(this.settings.selector),this.observer=null,this.init()}const s={src:"data-src",srcset:"data-srcset",selector:".lazyload"},r=function(){let t={},e=!1,s=0,o=arguments.length;"[object Boolean]"===Object.prototype.toString.call(arguments[0])&&(e=arguments[0],s++);for(;s<o;s++)!function(s){for(let o in s)Object.prototype.hasOwnProperty.call(s,o)&&(e&&"[object Object]"===Object.prototype.toString.call(s[o])?t[o]=r(!0,t[o],s[o]):t[o]=s[o])}(arguments[s]);return t};if(e.prototype={init:function(){if(!t.IntersectionObserver)return void this.loadImages();let e=this,s={root:null,rootMargin:"0px",threshold:[0]};this.observer=new IntersectionObserver(function(t){t.forEach(function(t){if(t.intersectionRatio>0){e.observer.unobserve(t.target);let s=t.target.getAttribute(e.settings.src),r=t.target.getAttribute(e.settings.srcset);"img"===t.target.tagName.toLowerCase()?(s&&(t.target.src=s),r&&(t.target.srcset=r)):t.target.style.backgroundImage="url("+s+")"}})},s),this.images.forEach(function(t){e.observer.observe(t)})},loadAndDestroy:function(){this.settings&&(this.loadImages(),this.destroy())},loadImages:function(){if(!this.settings)return;let t=this;this.images.forEach(function(e){let s=e.getAttribute(t.settings.src),r=e.getAttribute(t.settings.srcset);"img"===e.tagName.toLowerCase()?(s&&(e.src=s),r&&(e.srcset=r)):e.style.backgroundImage="url("+s+")"})},destroy:function(){this.settings&&(this.observer.disconnect(),this.settings=null)}},t.lazyload=function(t,s){return new e(t,s)},t.jQuery){const s=t.jQuery;s.fn.lazyload=function(t){return t=t||{},t.attribute=t.attribute||"data-src",new e(s.makeArray(this),t),this}}return e});

/*Ratings*/
!function(t){function a(a,r){t('.raterater-input[data-id="'+a+'"]').data("input").val(r).change()}function r(){g.each(function(){var a=t(this);if(p.mode==u&&("INPUT"==a.prop("tagName")||"SELECT"==a.prop("tagName"))){var r="input-"+y++,e=t('<div class="raterater-input"></div>').attr("data-id",r).attr("data-rating",a.val()).data("input",a);a.attr("data-id",r).attr("data-id",r).attr("data-rating",a.val()).data("input",a).after(e).hide(),l=a=e}l=a;var s=c(a);if(!s)throw"Error: Each raterater element needs a unique data-id attribute.";f[s]={state:"inactive",stars:null},"static"===a.css("position")&&a.css("position","relative"),a.addClass("raterater-wrapper"),a.html(""),t.each(["bg","hover","rating","outline","cover"],function(){a.append(' <div class="raterater-layer raterater-'+this+'-layer"></div>')});for(var n=0;n<p.numStars;n++)a.children(".raterater-bg-layer").first().append('<i class="fa fa-star"></i>'),a.children(".raterater-outline-layer").first().append('<i class="fa fa-star-o"></i>'),a.children(".raterater-hover-layer").first().append('<i class="fa fa-star"></i>'),a.children(".raterater-rating-layer").first().append('<i class="fa fa-star"></i>');p.isStatic||(a.find(".raterater-cover-layer").hover(o,h),a.find(".raterater-cover-layer").mousemove(d),a.find(".raterater-cover-layer").click(i))})}function e(){g.each(function(){var a;a=p.mode==u?t(this).parent().find('.raterater-input[data-id="'+c(this)+'"]'):t(this);var r=(c(a),p.width+"px"),e=Math.floor(p.starWidth/p.starAspect)+"px";a.css("width",r).css("height",e),a.find(".raterater-layer").each(function(){t(this).css("width",r).css("height",e)});for(var i=0;i<p.numStars;i++)t.each(["bg","hover","rating","outline"],function(){a.children(".raterater-"+this+"-layer").first().children("i").eq(i).css("left",i*(p.starWidth+p.spaceWidth)+"px").css("font-size",Math.floor(p.starWidth/p.starAspect)+"px")});var s=parseFloat(a.attr("data-rating")),d=Math.floor(s),o=s-d;n(a.find(".raterater-rating-layer").first(),d,o)})}function i(r){var e=t(r.target).parent(),i=c(e),s=f[i].whole_stars_hover+f[i].partial_star_hover;s=Math.round(100*s)/100,f[i].state="rated",f[i].stars=s,e.find(".raterater-hover-layer").addClass("rated"),"input"!=p.mode&&void 0!==window[p.submitFunction]&&"function"==typeof window[p.submitFunction]?window[p.submitFunction](i,s):a(i,s)}function s(t,a){var r=Math.floor(t/(p.starWidth+p.spaceWidth)),e=t-r*(p.starWidth+p.spaceWidth);if(e>p.starWidth&&(e=p.starWidth),e/=p.starWidth,p.step!==!1){var i=1/p.step;e=Math.round(e*i)/i}f[a].whole_stars_hover=r,f[a].partial_star_hover=e}function n(t,a,r){for(var e=(c(t.parent()),0);a>e;e++)t.find("i").eq(e).css("width",p.starWidth+"px");t.find("i").eq(a).css("width",p.starWidth*r+"px");for(var e=a+1;e<p.numStars;e++)t.find("i").eq(e).css("width","0px")}function d(a){var r=c(t(a.target).parent());if("hover"===f[r].state){var e=a.offsetX;void 0===e&&(e=a.pageX-t(a.target).offset().left),f[r].stars=s(e,r);var i=t(a.target).parent().children(".raterater-hover-layer").first();n(i,f[r].whole_stars_hover,f[r].partial_star_hover)}}function o(a){var r=c(t(a.target).parent());("rated"!==f[r].state||p.allowChange)&&(f[r].state="hover",t(a.target).parent().children(".raterater-rating-layer").first().css("display","none"),t(a.target).parent().children(".raterater-hover-layer").first().css("display","block"))}function h(a){var r=t(a.target).parent(),e=c(r);if(t(a.target).parent().children(".raterater-hover-layer").first().css("display","none"),t(a.target).parent().children(".raterater-rating-layer").first().css("display","block"),"rated"===f[e].state){var i=parseFloat(f[e].stars),s=Math.floor(i),d=i-s;return void n(r.find(".raterater-rating-layer").first(),s,d)}f[e].state="inactive"}function c(a){return t(a).attr("data-id")}var l,f={},p={},u="input",v="callback",g=null,y=0;t.fn.raterater=function(a){if(t.fn.raterater.defaults={submitFunction:"",allowChange:!1,starWidth:20,spaceWidth:5,numStars:5,isStatic:!1,mode:v,step:!1},p=t.extend({},t.fn.raterater.defaults,a),p.width=p.numStars*(p.starWidth+p.spaceWidth),p.starAspect=.9226,p.step!==!1&&(p.step=parseFloat(p.step),p.step<=0||p.step>1))throw"Error: step must be between 0 and 1";return g=this,r(),e(),this}}(jQuery);

//Initialize lazyload images
lazyload();

//initialize ratings on software lists
$('.ratebox').raterater({ 
	submitFunction: 'rateAlert',
	allowChange: false,
	starWidth: 15,
	spaceWidth: 2,
	numStars: 5,
	isStatic: true
});

$('.ratebox-reviews').raterater({ 
	submitFunction: 'rateAlert',
	allowChange: false,
	starWidth: 16,
	spaceWidth: 2,
	numStars: 5,
	isStatic: true
});

/*$('.dropdown').click(function(e){
  $(this).find('.dropdown-menu').toggleClass('open');
  $($(e.target).find('.down-caret').toggleClass('open-caret'));
  e.preventDefault();
  e.stopPropagation();

});

$(document).click(function(){
  $('.dropdown-menu').removeClass('open');
  $('.down-caret').removeClass('open-caret');
});*/

//call back func for initialized ratings
function rateAlert(id, rating)
{
    alert( 'Rating for ' + id + ' is ' + rating + ' stars!' );
}

function shake(div)
{                                                                                                                                                                                            
	var interval = 100;                                                                                                 
	var distance = 5;                                                                                                  
	var times = 8;                                                                                                      

	$(div).css('position','relative');                                                                                  

	for(var iter=0;iter<(times+1);iter++){                                                                              
	    $(div).animate({ 
	        left:((iter%2==0 ? distance : distance*-1))
	        },interval);                                   
	}                                                                                                              
	$(div).animate({ left: 0},interval);                                                                                
}


//Form Validation
//Form Validation

function validate_fullname(form) 
{
var min_length = 5; // min caracters to display the autocomplete
var max_length = 40;

var fullname = $('#fullname').val();

if (fullname.length >= min_length && fullname.length <= max_length) 
{
  check1 = true;
  $(status).html('');
} 
else 
{
  check1 = false;
  $(status).html('<span style="color: red; ">Full Name is not valid. It length should be between 5-40 characters.</span><br />');
}

check();
}


function validate_username(form) 
{
  console.log('ping');
var min_length = 5; // min caracters to display the autocomplete
var max_length = 16;

var username = $('#username').val();


if (username.length >= min_length && username.length <= max_length) 
{
  var nameRegex = /^[a-z][a-z0-9]*(?:_[a-z0-9]+)*$/;
  var validfirstUsername = $('#username').val().match(nameRegex);
  if(validfirstUsername == null)
  {
    $(status).html('<span style="color: red; ">User Name is not valid. Only characters a-z and 0-9 are acceptable.</span><br />');
    $('#username').focus();
    check2 = false;
    return false;
  }
  else
  {
    check2 = true;
    $(status).html('');
  }
} 
else 
{
  check2 = false;
  $(status).html('<span style="color: red; ">User Name is not valid. It length should be between 5-16 characters and starts with alphabet</span><br />');
}



check();
}

//company name validation
function validate_company(form) 
{
  var max_length = 40;

  var company = $('#company').val();

  if (company.length <= max_length) 
  {
    check3 = true;
    $(status).html('');
  } 
  else 
  {
    check3 = false;
    $(status).html('<span style="color: red; ">Company Name is not valid. It length should be between less than or equal to 40 characters,</span><br />');
  }

  check();
}


//company name validation
function validate_company_info(form) 
{
  var max_length = 200;

  var company_info = $('#company_info').val();

  if (company_info.length <= max_length) 
  {
    check4 = true;
    $(status).html('');
  } 
  else 
  {
    check4 = false;
    $(status).html('<span style="color: red; ">Company Info is not valid. It length should be between less than or equal to 200 characters,</span><br />');
  }

  check();
}


function validate_password(form) 
{
var min_length = 5; // min caracters to display the autocomplete
var max_length = 16;

var password = $('#password').val();

if (password.length >= min_length && password.length <= max_length) 
{
  check5 = true;
  $(status).html('');
} 
else 
{
  check5 = false;
  $(status).html('<span style="color: red; ">Password is not valid. It length should be between 5-16 characters.</span><br />');
}

check();
}

function validate_c_password(form) 
{
var min_length = 5; // min caracters to display the autocomplete
var max_length = 16;

var c_password = $('#c_password').val();
var password = $('#password').val();

if (c_password.length >= min_length && c_password.length <= max_length) 
{
  check6 = true;
  $(status).html('');
} 
else 
{
  check6 = false;
  $(status).html('<span style="color: red; ">Password is not valid. It length should be between 5-16 characters.</span><br />');
}

if(password != c_password)
{
  check6 = false;
  $(status).html('<span style="color: red; ">Password and Confirm Password should be same.</span><br />');

}
check();
}


function validate_email(form) 
{
  var email = $('#email').val();
  if(validateEmail(email))
  {
    check7 = true;
    $(status).html('');
  }
  else
  {
    check7 = false;
    $(status).html('<span style="color: red; ">Email is not valid.</span><br />');

  }
  check();
}

function validateEmail(email) { 
// http://stackoverflow.com/a/46181/11236

var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
return re.test(email);
}


function check()
{
  if(check1==true && check2==true && check3==true && check4==true && check5==true && check6==true && check7==true)
  {
    $(button).prop('disabled', false);
  }
} 

/*SEARCH*/
  var suggestionsjv = document.getElementById('suggestions');
  var show_suggestions = true;
  var $listItems = $('.suggestion');
  
  $("#q").keypress(function(e) {
      if (e.which == 13) {
        $("#main-search-form").submit();
      }
  });

  $(document).keydown(function(e) {
    var key = e.keyCode;
      if(key == 27)
      {
        suggestionsjv.style.display = "none";
        return;
      }
  });

  $("#q").keydown(function(e) {
        var key = e.keyCode,
          $selected = $listItems.filter('.selected'),
          $current;
          if(key == 27)
          {
            suggestionsjv.style.display = "block";
            return;
          }
        if ( key != 40 && key != 38 ) return;
        $listItems.removeClass('selected');
        if ( key == 40 ) // Down key
        {
            if ( ! $selected.length || $selected.is(':last-child') ) {
                $current = $listItems.eq(0);
            }
            else {
                $current = $selected.next();
            }
        }
        else if ( key == 38 ) // Up key
        {
            if ( ! $selected.length || $selected.is(':first-child') ) {
                $current = $listItems.last();
            }
            else {
                $current = $selected.prev();
            }
        }
        //$current.addClass('selected');
      $(this).val ( $current.addClass('selected').text() );
  });

  $(document).ready(function() {
    var qw = $('#search-group-main').width();
    suggestionsjv.style.width = qw+"px";
  });

  $('#q').on('input',function(e)
  {
    var query = $(this).val();

    var qw = $('#search-group-main').width();
    suggestionsjv.style.width = qw+"px";


    if(query.length == 0) 
    {
      suggestionsjv.style.display = "none";
      return;
    }
      if(show_suggestions == false)
      {
        show_suggestions = true;
        return false;
      }
    //var api = "https://google.com/complete/search?client=firefox&q="+query;
    var api = "http://standaloneinstaller.com/search_ajax.php?q="+query;
    var suggestions = $('#suggestions');
    
    $.ajax({
      url: api,
      type: "GET",
   
      dataType: "json",
      contentType: false,
      processData: false,
      success: function(json) 
      {
        if(query == json[0])
        {
          console.log(json);

          var results = json[1];
          suggestions.empty();
          suggestionsjv.style.display = "block";
          for (var i = 0; i < results.length; i++) 
          {
            var text = results[i];
            var item = document.createElement('li');
            item.className = "suggestion";
            item.innerHTML = text;
            item.onclick = clicked;
            suggestionsjv.appendChild(item);
          };
          $listItems = $('.suggestion');
        }
      },
      complete: function() {
      }
    });
  });
  function clicked()
  {
    show_suggestions = false;
    var q = document.getElementById('q');
    q.value = this.innerText; 
    suggestionsjv.style.display = "none";
    q.focus();
  }
  $(document).click(function(e) {
      var target = e.target;
      if (!$(target).is('#suggestions') && !$(target).parents().is('#suggestions')) {
          $('#suggestions').hide();
      }
  });

  $(window).resize(function() 
  {
    var qw = $('#search-group-main').width();
    suggestionsjv.style.width = qw+"px";
  });