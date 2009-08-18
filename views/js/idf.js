
$(document).ready(function() {
   
   alert("js ok");
   
});

function registerComponentFormSubmit() {
   $("input#componentSubmit").click(function(e) {
      e.preventDefault();
      
      var action = $("input#componentSubmit").attr("action");
      var method = $("input#componentSubmit").attr("method");
      
      var data = "";
      
      $.ajax({
         type: method,
         url: action,
         data: data,
         error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert("error");
         },
         success: function(xml, textStatus) {
            alert("success");
         }
      });
      
   });
}
