
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
         data[data.length] = $(this).attr("name") + "=" + $(this).attr("value");
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
            // $("div#content").html("An error occurred while submitting. Please 'refresh' this page and retry.");
         },
         success: function(result) {
            // $("div#content").html(result);
            alert(result);
         }
      });
      
   });
}
   
function registerHoverEffect() {
   $("table#componentTable tr").each(function() {
      $(this).mouseout(function() {
         $(this).removeClass("componentHover");
         // eventElement.removeClassName("wfcListComboHover");
         // eventElement.getFirstChild().getFirstChild().setStyle({display:"none"});
      });
      $(this).mouseover(function() {
         $(this).addClass("componentHover");
         // eventElement.addClassName("wfcListComboHover");
         // eventElement.getFirstChild().getFirstChild().setStyle({display:"block"});
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
         
         // if id is toggle all then toggle all!!
         
         var toggle = ($(this).find("input").attr("checked"))?0:1;
         $(this).find("input").attr("checked",toggle);
      });
   });
}

function toggleAllComponents() {
   var checked = $("form#componentForm input#toggleAll").attr("checked")?1:0;
   $("form#componentForm").find("input").attr("checked",checked);
}