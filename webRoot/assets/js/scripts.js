
//
// Executes on DOM ready
//
App.subscribe("init", function(){
	
	//	
	// Add Syntax Highlighting
	//
	SyntaxHighlighter.all();
	
	//
	// Add Search Interactions
	//
	$("#search").bind('focus', function(){
		$(this).parent().find("label").animate({opacity:'0.5'}, 200);
	}).bind('blur', function(){
		$(this).parent().find("label").animate({opacity:'1'}, 200);
	}).bind('keypress', function(){
		$(this).parent().find('label').hide();
	}).bind('keyup', function(){
		if($(this).val() == ''){
			$(this).parent().find('label').show();
		}
	});
	
	
	//
	// Fancy Dropdown
	//
	$(".sdropdown").bind("mouseover", function(){
		$(this).find("ul").stop().slideDown(200);
	}).bind("mouseout", function(){
		$(this).find("ul").stop().slideUp(200);
	});

	//
	// Temporary: REMOVE
	// Change page color
	//
	var colors = [ "jquery", "jquery-ui", "jquery-mobile", "jquery-project" ],
		color_string = colors.join(' ');
	$("ul.projects").delegate("li:lt(3)", "click", function(e) {
		e.preventDefault();
		$(document.documentElement)
			.removeClass(color_string)
			.addClass(this.className);
		window.location.hash = this.className;
	});
	
	if (window.location.hash && $.inArray(window.location.hash.substr(1), colors) > -1) {
		$(document.documentElement)
			.removeClass(color_string)
			.addClass(window.location.hash.substr(1));
	}
	
	//
	// Project Select Show/Hide
	//
	$(".toggle-projects").bind("click", function(e){
		e.preventDefault();
		var $this = $(this).toggleClass("open");
		
		if ($this.hasClass("open")) {
		    $("body > header").stop(true, false).animate({"marginTop":"140px"}, 500);
		} else {
		    $("body > header").stop(true, false).animate({"marginTop":"0"}, 500);
		}
		
	});

	$(".presentations img, .books img").each(function (i, el) {
		var $img = $(this),
			$span = $img.parent();
		
		$span.css("background-image", "url(" + $img.attr('src') + ")");
		$img.css("visibility", "hidden");
	});
	
	// Plugin Site Code
	// Templates
  var summaryTmpl = '<li class="${pluginType}"><h2><a data-type="plugin" href="/${owner}-${name}">${name}</a></h2><p>${description}</p><em><a data-type="owner" href="/${owner}">${owner}</a></li><hr>',
      ownerTmpl = '<h1>${name}</h1><p>As soon as we implement the user model, we will have an owner view</p><ul><li>plugin</li><li>plugin</li><li>plugin</li></ul></li>',
      pluginTmpl = '<h1>${name}</h1><p>${description}</p> Maintained by {{each maintainers}}<em><a data-type="owner"href="/${owner}">${name}</a></em>{{if $index < maintainers.length-1 }}, {{/if}}{{/each}}</li>',

  // App data stores
      pluginsLookup = {},
      ownerLookup = {},
      
  // Views
      routes = {
        plugin : function( route ){
          history.pushState({ path: this.path }, '', pluginsLookup[route].owner + '/' + pluginsLookup[route].name);
          console.log('plugin')
        },
        owner : function( route ){              
          history.pushState({ path: this.path }, '', '/' + route);
          console.log(route)
        }
      },
      views = {            
        summary : function( plugins ){
          $('#pageName').html( 'All Plugins' );
          $('#summary').append( summaryTmpl, plugins, null )
        },
        owner : function( ownerRoute ){
          $('#pageName').html( ownerRoute );
          
          $('#pageContent').children().hide();

          if ( !$('#' + ownerRoute).length ) {
            $('#pageContent').append('<div id="' + ownerRoute + '"></div>');
            $('#' + ownerRoute).append(ownerTmpl, ownerLookup[ownerRoute], null);
          } else {
            $('#' + ownerRoute).show()
          }

        },
        plugin : function( pluginRoute ){
          $('#pageName').html( pluginRoute );

          $('#pageContent').children().hide();

          if ( !$('#' + pluginRoute).length ) {
            $('#pageContent').append('<div id="' + pluginRoute + '"></div>');
            $('#' + pluginRoute).append(pluginTmpl, pluginsLookup[pluginRoute], null);
          } else {
            $('#' + pluginRoute).show()
          }
        }
      };

  // Get all docs
  $.getJSON('http://plugins-v3.jquery.com:5984/plugins/_all_docs?callback=?&include_docs=true', function( responses ){
    _.each( responses.rows, function( response ){
      if ( response.doc.name ){
        // We're loading the page, render the summary view
        views.summary( response.doc );
        // Add plugins to the plugins lookup
        pluginsLookup[response.id] = response.doc;
        // Add plugins to the owner lookup
        ownerLookup[response.doc.owner] ? ownerLookup[response.doc.owner].push(response.doc) : ownerLookup[response.doc.owner] = [response.doc];
      }
    })
  })
  
  // Event Bindings
  $('a').live('click', function( event ) {
    event.preventDefault();
    var route = $(this).attr('href').replace('/', ''),
        type = $(this).attr('data-type');
        
    routes[ type ]( route )
    views[ type ]( route )
  })

  $(window).bind('popstate', function() {
    event.preventDefault();
    var route = location.pathname.replace('/', '').replace('/', '-');
    if ( location.pathname == '/') {
      $('#pageContent').children('div').hide();
      $('#summary').show();
    } else {
      $('#pageContent').children().hide();
      $('#' + route ).show();

    }
  })

});




