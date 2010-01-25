
$(function() {
	
	// Make components table sortable
	$('table#componentTable').tablesorter({
		headers: { 
			0: { 
				sorter: false
			}
		}
	});
	
	registerComponentFormSubmit();
	registerHoverEffect();
	registerShowFeedbackDialog();
	registerToggleAllCheckbox();
	registerToggleSingleClickEvent();
	
	// Select all components by default
	$('form#componentForm input#toggleAll').attr('checked',1);
	toggleAllComponents();
	
	alert('js ok');
	
});

function clearForm() {
   $('div.select').css({
		display:'none'
	});
}

function registerComponentFormSubmit() {
   $('input#componentSubmit').click(function(e) {
      e.preventDefault();
      
      var action = $('form#componentForm').attr('action');
      var method = $('form#componentForm').attr('method');
      
      // Send names and values of form input fields
      var data = [];
      $('form#componentForm :input').each(function() {
         if ($(this).attr('type') == 'checkbox') {
            if ($(this).attr('checked') == 1) {
               data[data.length] = $(this).attr('name') + '=' + $(this).attr('value');
            }
         }
         else {
            data[data.length] = $(this).attr('name') + '=' + $(this).attr('value');
         }
      });
      data = data.join('&');
      
      $.ajax({
         type: method,
         url: action,
         cache: false,
         data: data,
         error: function(result) {
            var answer = confirm('An error has occurred. Try again?');
            if (answer) {
               // clearForm();
			   location.reload(true);
            }
         },
         success: function(result) {
            $('span#resultFileResponse').html('Download file: <a href=\'files/download/' + result + '\' >' + result + '</a> ( Right-click + \'Save link as &hellip;\' )');
         }
      });
      
   });
}
   
function registerHoverEffect() {
   $('table#componentTable tbody tr').each(function() {
      $(this).mouseout(function() {
         $(this).removeClass('componentHover');
      });
      $(this).mouseover(function() {
         $(this).addClass('componentHover');
      });
   });
}

function registerShowFeedbackDialog() {

	$('div.feedback a').click(function() {
		
		resetFeedbackForm();
		
		$('div#Feedback').dialog({
			bgiframe: true,
			buttons: {
				'Send': function() {
					sendFeedback();
				}
			},
			close: function() {
				$(this).dialog('destroy');
				resetFeedbackForm();
			},
			closeOnEscape: false,
			draggable: false,
			modal: true,
			open: function() {
				resetFeedbackForm();
				$(this).parent().css({
					top: '40px'
				});
			},
			resizable: false,
			width: 600
		});
	});
	
}

function resetFeedbackForm() {
	
	$('div#Feedback').data('status', '');
	
	$('div#Feedback textarea').val('');
	$('div#Feedback div#ThankYou').hide();
	$('div#Feedback div#FeedbackForm').show();
	$('div#Feedback div.message').hide().attr('class', 'message').html('');
	
}

function sendFeedback() {
	if ($('div#Feedback').data('status') == 'sending') return false;
	
	$('div#Feedback').data('status', 'sending');
	
	$.ajax({
		url: 'ajax/sendmail.php',
		type: 'POST',
		data: {
			username: $('div#Feedback input#username').val(),
			email: $('div#Feedback input#email').val(),
			comment: $('div#Feedback textarea#comment').val()
		},
		dataType: 'json',
		complete: function() {
			// alert('complete');
		},
		error: function(message) {
			$('div#Feedback div.message').addClass('error').html('AJAX Error: '+message).show();
		},
		success: function(data){
			if (typeof data.sent != 'undefined' && data.sent == '1') {
				
				// $('div#Feedback div#FeedbackForm').slideUp('fast');
				$('div#Feedback div.message').addClass('success').html('Thank you.').show();
				// $('div#Feedback textarea').val('');
				
				var executionTime = setTimeout(function() {
					$('div#Feedback').dialog('destroy');
				}, 2000);
			}
			else {
				var error = 'Your message was not sent.<br/>';
				if (typeof data.error != 'undefined' && data.error.length > 0) {
					error += data.error;
				}
				$('div#Feedback div.message').addClass('error').html(error).show();
			}
		}
	});
	
}

function registerToggleAllCheckbox() {
   $('form#componentForm input#toggleAll').change(function() {
      toggleAllComponents();
   });
}

function registerToggleSingleClickEvent() {
	
	$('input#toggleAll').click(function() {
		toggleAllComponents();
	});
	
	// Disable single checkbox click
	$('table#componentTable tbody tr input').each(function() {
		if ($(this).attr('type') == 'checkbox') {
			$(this).click(function(event) {
				event.preventDefault();
			});
		}
	});
	
	// Pass row click to checkbox
	$('table#componentTable tbody tr').each(function() {
		$(this).mouseup(function() {
			var checkbox = $(this).find('input');
			var toggle = (checkbox.attr('checked'))?0:1;
			if (toggle == 1) {
				$(this).addClass('selected');
			}
			else {
				$(this).removeClass('selected');
			}
			checkbox.attr('checked',toggle);
		});
   });
}

function toggleAllComponents() {
	var checked = $('form#componentForm input#toggleAll').attr('checked')?1:0;
	$('form#componentForm').find('input').each(function() {
		if ($(this).attr('type') == 'checkbox' && $(this).attr('id') != 'toggleAll') {
			$(this).attr('checked',checked);
			if (checked == 1) {
				$(this).parent().parent().addClass('selected');
			}
			else {
				$(this).parent().parent().removeClass('selected');
			}
		}
	});
}