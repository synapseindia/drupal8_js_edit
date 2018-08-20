
(function ($, Drupal) {
	
	Drupal.behaviors.jsEdit = {
		attach: function(context, settings) {
			$('body').addClass('has-js');
			var editor = ace.edit("editor");
			editor.getSession().setUseWorker(false);
			editor.setTheme("ace/theme/chrome");
			editor.getSession().setMode("ace/mode/javascript");

			editor.getSession().on('change', function(e) {
				setTextareaValue();
			});

			var setTextareaValue = function() {
				$('#edit-js-code').val(editor.getValue());
			}

			$('.disable-ace').click(function() {
				var $this = $(this);
				$this.toggleClass('ace-disabled');
				$text = $this.text() == 'Disable Code Editor' ? 'Enable Code Editor' : 'Disable Code Editor';
				$this.text($text);
				$('.form-item-js-code .form-textarea-wrapper, .ace-editor').toggle();
			});
		}
	}

})(jQuery, Drupal);