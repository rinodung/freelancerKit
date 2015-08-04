$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});

// Support for AJAX loaded modal window.
// Focuses on first input textbox after it loads the window. 

$('[data-toggle="mainmodal"]').bind('click',function(e) {
  NProgress.start();
  e.preventDefault();
  var url = $(this).attr('href');
 
  if (url.indexOf('#') === 0) {
    $('#mainModal').modal('open');
  } else {
    $.get(url, function(data) { 
                        $('#mainModal').modal();
                        $('#mainModal').html(data);

                        
    }).success(function() { NProgress.done();  });
  }
}); 

//Ajax loaded content
$(document).on("click", '.ajax', function (e) {
  e.preventDefault();
  NProgress.start();

  $(".message-list ul.list-striped li").removeClass('active');
  $(this).parent().addClass('active');
  
  //$("html, body").animate({ scrollTop: 0 }, 600);
  var url = $(this).attr('href');
  if (url.indexOf('#') === 0) {
    
  } else {
    $.get(url, function(data) { 
                        $('#ajax_content').html(data);
                        $(".message_content:gt(1)").hide();
                        $('#ajax_content').fadeIn('fast');
    }).success(function() { 
            $(".message_content:gt(1)").fadeIn('slow');  
            
            $(".scroll-content").mCustomScrollbar({theme:"dark-2"}); 
            NProgress.done(); 
        });
  }
}); 

//Ajax background load
  $(document).on("click", '.ajax-silent', function (e) {
  e.preventDefault();
  NProgress.start();
  var url = $(this).attr('href');
  
    $.get(url, function(data) { 
                        
    }).success(function() { $('.message-list ul li a').first().click(); NProgress.done(); });
  
}); 

  //Ajax background load
  $(document).on("change", '.task-check', function (e) {
  e.preventDefault();
  NProgress.start();
  var url = $(this).data('link');
  
    $.get(url, function(data) { 
                        
    }).success(function() { NProgress.done(); });
  
}); 

//message list delete item
$(document).on("click", '.message-list-delete', function (e) {

  $(this).parent().fadeTo("slow", 0.01, function(){ //fade
             $(this).slideUp("fast", function() { //slide up
                 $(this).remove(); //then remove from the DOM
             });
         });
});  


//message reply

$(document).on("click", '#reply', function (e) {
 
 $("#reply").animate({'height': '240px'}, {queue: false, complete: function(){ 
    $('#reply').wysihtml5({"size": 'small'});
    $('.reply #send').fadeIn('slow');

    } });


}); 
$(".nano").nanoScroller();
//Ajax reply form submit
  $(document).on("click", '.ajaxform #send', function (e) {

    var content = $('textarea[name="message"]').html($('#reply').code());
    var url = $(this).closest('form').attr('action'); 
    var active = $(this);
    $.ajax({
           type: "POST",
           url: url,
           data: $(this).closest('form').serialize(),
           success: function(data)
           {
               $('.message-list li.active a.ajax').click();
               
               $(".ajaxform #send").html('<i class="fa fa-check-circle-o"></i>');
              
                  $('.message-content-reply, #timeline-comment').slideUp('slow').animate(
                    { opacity: 0 },
                    { queue: false, duration: 'slow' }
                  );
                 $(".note-editable").html("");
                 var reload = active.closest('form').data('reload');
                 if(reload) {
                     $('#'+reload).load(document.URL + ' #'+reload, function() {
                         $('#'+reload+' ul li:nth-child(2) .timeline-panel').addClass("highlight");
                         $('#'+reload+' ul li:nth-child(2) .timeline-panel').delay("5000").removeClass("highlight");
                         
                        summernote();
                     }); 
                     
                 }
                 
                
           },
           error: function(data)
           {
               $('.message-list li.active a.ajax').click();
               
               $(".ajaxform #send").html('<i class="fa fa-check-circle-o"></i>');
              
                  $('.message-content-reply, #timeline-comment').slideUp('slow').animate(
                    { opacity: 0 },
                    { queue: false, duration: 'slow' }
                  );
                 $(".note-editable").html("");
                 var reload = active.closest('form').data('reload');
                 if(reload) {
                     $('#'+reload).load(document.URL + ' #'+reload, function() {
                         $('#'+reload+' ul li:nth-child(2) .timeline-panel').addClass("highlight");
                         $('#'+reload+' ul li:nth-child(2) .timeline-panel').delay("5000").removeClass("highlight");
                         
                        summernote();
                     }); 
                     
                 }
                 
                
           }
         });

    return false;
});


  $(document).on("click", '.taskform #send', function (e) {

    var content = $('textarea[name="description"]').html($('.note-editable').code());
    var buttontext = $(".taskform #send").val();
    //$(".taskform #send").val('...');
    $("#showloader").show();
    var url = $(this).closest('form').attr('action'); 
    var active = $(this);
    $.ajax({
           type: "POST",
           url: url,
           data: $(this).closest('form').serialize(),
           success: function(data)
           {
               
               //$(".taskform #send").val(buttontext);
              $(".taskform")[0].reset();
                 $(".note-editable").html("");
                 $("#showloader").hide();
                
           },
           error: function(data)
           {
               
               //$(".taskform #send").val(buttontext);
              $(".taskform")[0].reset();
                 $(".note-editable").html("");
                 $("#showloader").hide();
                
           }
         });

    return false;
});


//Project Notes
$(document).on("click", '.note-form #send', function (e) {
var button = this;
var content = $('textarea[name="note"]').html($('#textfield').code());
    var url = $(this).closest('form').attr('action'); 
    $.ajax({
           type: "POST",
           url: url,
           data: $(this).closest('form').serialize(),
           success: function(data)
           {
            
            var value = $( button ).text();
            var str = value.replace('<i class="fa fa-spinner fa-spin"></i> ', "");
            $(button).html(str);
             $('#changed').fadeOut('slow');
           },
           error: function(data)
           {
            
            var value = $( button ).text();
            var str = value.replace('<i class="fa fa-spinner fa-spin"></i> ', "");
            $(button).html(str);
             $('#changed').fadeOut('slow');
           }
         });

    return false;
  
}); 
$(document).on("focus", '#_notes .note-editable', function (e) {
$('#changed').fadeIn('slow');
}); 
$(document).on("click", '#_notes .addtemplate', function (e) {
$('#changed').fadeIn('slow');
}); 



$('.to_modal').click(function(e) {
    e.preventDefault();
    var href = $(e.target).attr('href');
    if (href.indexOf('#') == 0) {
        $(href).modal('open');
    } else {
        $.get(href, function(data) {
            $('<div class="modal fade" >' + data + '</div>').modal();
        });
    }
});





//Clickable rows
	$(document).on("click", 'table#projects td, table#clients td, table#invoices td, table#cprojects td, table#cinvoices td, table#quotations td, table#messages td, table#cmessages td, table#subscriptions td, table#csubscriptions td, table#tickets td, table#ctickets td', function (e) {
	    
      var id = $(this).parent().attr("id");
	    if(id && !$(this).hasClass("noclick")){
	   		var site = $(this).closest('table').attr("rel")+$(this).closest('table').attr("id");
	    	if(!$(this).hasClass('option')){window.location = site+"/view/"+id;}
	    } 
  	});
      $(document).on("click", 'table#media td', function (e) {
	    var id = $(this).parent().attr("id");
	    if(id){
	    	var site = $(this).closest('table').attr("rel");
	    	if(!$(this).hasClass('option')){window.location = site+"/view/"+id;}
	    }
    });
      $(document).on("click", 'table#custom_quotations_requests td', function (e) {
      var id = $(this).parent().attr("id");
      if(id){
        var site = $(this).closest('table').attr("rel");
        if(!$(this).hasClass('option')){window.location = "quotations/cview/"+id;}
      }
    });
      $(document).on("click", 'table#quotation_form td', function (e) {
      var id = $(this).parent().attr("id");
      if(id){
        var site = $(this).closest('table').attr("rel");
        if(!$(this).hasClass('option')){window.location = "formbuilder/"+id;}
      }
    });



    
      /* -------------- Summernote WYSIWYG Editor ------------- */
         function summernote(){
              $('.summernote').summernote({
                height:"200px",
                toolbar: [
                  ['style', ['style']], // no style button
                  ['style', ['bold', 'italic', 'underline', 'clear']],
                  ['fontsize', ['fontsize']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['height', ['height']],
                  ['insert', []], //for Custom Templates
                ]
              });
              var postForm = function() {
                var content = $('textarea[name="content"]').html($('#textfield').code());
              }
         }
         summernote();
          $('.summernote-note').summernote({
            height:"360px",
            toolbar: [
              ['insert', []], //for Custom Templates
              ['style', ['style']], // no style button
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['fontsize', ['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              
            ]
          });
          var postForm = function() {
            var content = $('textarea[name="note"]').html($('#textfield').code());
          }
        
          $('.summernote-big').summernote({
            height:"450px",
            toolbar: [
              ['insert', []], //for Custom Templates
              ['style', ['style']], // no style button
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['fontsize', ['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              
            ]
          });

        

      /* -------------- Summernote WYSIWYG Editor ------------- */


      //Custom select plugin
      $(".chosen-select").chosen({disable_search_threshold: 4, width: "100%"});


      //notify 
      
      $('.notify').animate({
            opacity: 1,
            right: "10px",
          }, 800, function() {
            $('.notify').delay( 3000 ).fadeOut();
          });
      

      // List striped
      $("ul.striped li:even").addClass("listevenitem");

      //Custom Scrollbar
      $(".scroll-content").mCustomScrollbar({theme:"dark-2"});
      $(".scroll-content-2").mCustomScrollbar({theme:"dark-2"});
      
      //Form validation
      $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();

      $('.use-tooltip').tooltip();
       $('.tt').tooltip();
       
      $('.po').popover({html:true});
      
       $(document).on("click", '.po-close', function (e) {
          $('.po').popover('hide');
      });
      $(document).on("click", '.po-delete', function (e) {
          $(this).closest('tr').fadeOut(400);
      });
       
       $('.date-picker').datepicker();
        $('#timepicker1').timepicker({
          minuteStep: 1,
          showSeconds: true,
          showMeridian: false
        });
        
        /* 
        $('.colorpicker').colorpicker();
        $('.colorpicker input').click(function() {
          $(this).parents('.colorpicker').colorpicker('show');
        })
        */

        // Checkbox Plugin

        $(".checkbox").labelauty();
        $(".checkbox-nolabel").labelauty({ label: false });

        //Checkbox for slider enable/disable
        $( ".lbl" ).click(function(){
          var isDisabled = $( "#slider-range" ).slider( "option", "disabled" );
          if(isDisabled){
            $( "#slider-range" ).slider( "option", "disabled", false );
          }else{
            $( "#slider-range" ).slider( "option", "disabled", true );
          }
          
        });


        //slider config
        $( "#slider-range" ).slider({
          range: "min",
          min: 0,
          max: 100,
          value: 1,
          slide: function( event, ui ) {
            $( "#progress-amount" ).html( ui.value );
            $( "#progress" ).val( ui.value );
          }
        });

        //upload button
        $(document).on("change", '#uploadBtn', function (e) {
          var value = $( this ).val().replace(/\\/g, '/').replace(/.*\//, '');
            $("#uploadFile").val(value);
        });
        $(document).on("change", '#uploadBtn2', function (e) {
          var value = $( this ).val().replace(/\\/g, '/').replace(/.*\//, '');
            $("#uploadFile2").val(value);
        });

        //button loaded on click
        $(document).on("click", '.button-loader', function (e) {
          var value = $( this ).text();
            $(this).html('<i class="fa fa-spinner fa-spin"></i> '+ value);
        });
        
        //on todo-checkbox click
         $(document).on("click", '.todo-checkbox', function (e) {
             
           var url = $(this).data('link');
           //$(this).attr("checked");
           if($(this).closest('li').hasClass("done")){
               $(this).closest('li').removeClass("done");
           }else{
               $(this).closest('li').addClass("done");
           }
            $.get(url, function(data) { 
                                
            }).success(function() {  });
             
        
        });
        //on todo click
         $(document).on("click", '.todo li p.name', function (e) {
           
                $(this).closest("li").toggleClass( "slidedown" );
        
        });

        //message reply slide down
        $(document).on("click", '.message-reply-button', function (e) {
        //button loaded on click
        $(document).on("click", '.button-loader', function (e) {
          var value = $( this ).text();
            $(this).html('<i class="fa fa-spinner fa-spin"></i> '+ value);
        });
        $('.summernote-ajax').summernote({
            height:"200px",
            toolbar: [
              //['style', ['style']], // no style button
              ['style', ['bold', 'italic', 'underline', 'clear']],
              ['fontsize', ['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              ['insert', []], //for Custom Templates
            ]
          });
          $(".message-content-reply").slideDown('slow').animate(
            { opacity: 1 },
            { queue: false, duration: 'slow' }
          );
        })

        //Timeline Comment field slide down
        $(document).on("click", '.open-comment-box', function (e) {
          $("#timeline-comment").slideDown('slow').animate(
            { opacity: 1 },
            { queue: false, duration: 'slow' }
          );

        });

        //Mobile Menu
        $(document).on("click", '.menu-trigger', function (e) {
          $(".side").addClass( 'menu-action');
          $(".sidebar-bg").addClass( 'show');
          /*$(".sidebar, .navbar-header").addClass( 'show');*/


        });
        $(document).on("click", '.content-area', function (e) {
          $(".side").removeClass( 'menu-action');
          $(".sidebar-bg").removeClass( 'show');
          /*$(".sidebar, .navbar-header").removeClass( 'show');*/

        });

        //check all checkboxes
        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
        $('#checkAll, .bulk-box').click(function(){
        if($('.bulk-box:checked').length){
          $("#bulk-button").addClass("btn-success");
        }else{
          $("#bulk-button").removeClass("btn-success");
        }
      });
      $(".bulk-dropdown li").click(function(){
        NProgress.start();
        var values = $('input:checkbox:checked.bulk-box').map(function () {
          return this.value;
        }).get();
        $('#list-data').val(values);
        var action = $("#bulk-form").attr('action');
        $("#bulk-form").attr('action', action+$(this).data("action"));
        $('#bulk-form').submit();
        
      });

      //bulk action setter
$(document).on("click", '.bulk-dropdown ul li a', function (e) {
  var action = $("#bulk-form").attr('action');
  $("#bulk-form").attr('action', action+$(this).data("action"));

});

      //fade in
$(document).on("click", '#fadein', function (e) {
$(".fadein").toggleClass("slide");


});
        //Mobile Menu hide
      /*  $(document).on("click", '.content-area', function (e) {
          if ($('.side').attr('class') != 'hidden-xs') {
          $(".side").addClass( 'hidden-xs');
          $(".sidebar").toggle().animate(
            { display: 'block'},
            { queue: false, duration: 'slow' }
          );
          }

        }); */
        
        
        
  




