jQuery(function ( $ ) {

	var frame;

	// Прикрепить файл к редакции
	$(document).on('click', '.js-add-file', function ( event ) {

		event.preventDefault();
		// Если окно загрузки уже доступно, просто откроем его
		if( frame ) {
			frame.open();
			return;
		}

		// В противном случае, создадим новое
		frame = wp.media({
			title   : 'Выберите файл',
			button  : {
				text: 'Использовать этот файл'
			},
			multiple: false  // Если нужна возможность крепить одним махом несколько файлов
		});

		// показать инфу о прикрепленном файле
		frame.on('select', function () {

			// Получим объект со всей информацией о выбранном файле
			var attachment = frame.state().get('selection').first().toJSON();

			$('.js-add-wrap').html('<div class="add_file js-add_file_itm">' +
				'<input type="hidden" name="add_file_id" value="' + attachment.id + '" />' +
				'<div class="add_file_icon"><img src="' + attachment.icon + '" alt="" /></div>' +
				'<p class="add_file_name">' + attachment.title + '</p>' +
				'<a href="#" class="button button-primary button-large js-add-file-remove">Открепить файл</a>' +
				'</div>');

		});

		// Откроем файл
		frame.open();
	});

	// отцепить приложенный файл от редакции
	$(document).on('click', '.js-add-file-remove', function ( event ) {
		event.preventDefault();
		$(this).closest('.js-add_file_itm').remove();
	});
});