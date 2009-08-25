
$(document).ready(function() {
   
   registerComponentFormSubmit();
   registerHoverEffect();
   registerToggleAllCheckbox();
   registerToggleSingleClickEvent();
   
   // Select all components by default
   $("form#componentForm input#toggleAll").attr("checked",1);
   toggleAllComponents();
   
   alert("js ok");
   
});

function clearForm() {
   $("div.select").css("display","none");
}

function registerComponentFormSubmit() {
   $("input#componentSubmit").click(function(e) {
      e.preventDefault();
      
      var action = $("form#componentForm").attr("action");
      var method = $("form#componentForm").attr("method");
      
      // Send names and values of form input fields
      var data = [];
      $("form#componentForm :input").each(function() {
         if ($(this).attr("type") == "checkbox") {
            if ($(this).attr("checked") == 1) {
               data[data.length] = $(this).attr("name") + "=" + $(this).attr("value");
            }
         }
         else {
            data[data.length] = $(this).attr("name") + "=" + $(this).attr("value");
         }
      });
      data = data.join("&");
      
      $.ajax({
         type: method,
         url: action,
         cache: false,
         data: data,
         error: function(result) {
            var answer = confirm("An error has occurred. Try again?");
            if (answer) {
               // clearForm();
			   location.reload(true);
            }
         },
         success: function(result) {
            $("span#resultFileResponse").html("Download file: <a href='files/download/" + result + "' >" + result + "</a> ( Right-click + 'Save link as &hellip;' )");
         }
      });
      
   });
}
   
function registerHoverEffect() {
   $("table#componentTable tr").each(function() {
      $(this).mouseout(function() {
         $(this).removeClass("componentHover");
      });
      $(this).mouseover(function() {
         $(this).addClass("componentHover");
      });
   });
}

function registerToggleAllCheckbox() {
   $("form#componentForm input#toggleAll").change(function() {
      toggleAllComponents();
   });
}

function registerToggleSingleClickEvent() {
	
	// Disable single checkbox click
	$("input").each(function() {
		$(this).click(function(event) {
			event.preventDefault();
		});
	});
	
	// Pass row click to checkbox
	$("table#componentTable tr").each(function() {
		$(this).mouseup(function() {
			var checkbox = $(this).find("input");
			var toggle = (checkbox.attr("checked"))?0:1;
			
			if (toggle == 1) {
				$(this).addClass("selected");
			}
			else {
				$(this).removeClass("selected");
			}
			
			checkbox.attr("checked",toggle);
			if (checkbox.attr("id") == "toggleAll") {
			   toggleAllComponents();
			}
		});
   });
}

function toggleAllComponents() {
	var checked = $("form#componentForm input#toggleAll").attr("checked")?1:0;
	$("form#componentForm").find("input").each(function() {
		if ($(this).attr("type") == "checkbox") {
			$(this).attr("checked",checked);
			if (checked == 1) {
				$(this).parent().parent().addClass("selected");
			}
			else {
				$(this).parent().parent().removeClass("selected");
			}
		}
	});
}