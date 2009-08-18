
$(document).ready(function() {
   
   registerComponentFormSubmit();
   registerHoverEffect();
   registerToggleAllCheckbox();
   // registerToggleSingleClickEvent();
   
   // Select all components by default
   $("form#componentForm input#toggleAll").attr("checked",1);
   toggleAllComponents();
   
   alert("js ok");
   
});

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
      
      // Display 'load' state
      // $("div#content").html("<img src=\"/images/animated.gif\" alt=\"\" /> Your assessment is being submitted. Please wait ...");
      
      $.ajax({
         type: method,
         url: action,
         cache: false,
         data: data,
         error: function(result) {
            $("span#resultFileResponse").html("An error occurred while submitting. Try again, and refresh if it still does not work.");
         },
         success: function(result) {
            // $("div#content").html(result);
            $("span#resultFileResponse").html("Download file: <a href='" + result + "' >" + result + "</a>");
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
   
   $("table#componentTable tr").each(function() {
      $(this).mouseup(function() {
         var checkbox = $(this).find("input");
         var toggle = (checkbox.attr("checked"))?0:1;
         
         checkbox.attr("checked",toggle);
         if (checkbox.attr("id") == "toggleAll") {
            toggleAllComponents();
         }
      });
   });
}

function toggleAllComponents() {
   var checked = $("form#componentForm input#toggleAll").attr("checked")?1:0;
   $("form#componentForm").find("input").attr("checked",checked);
}