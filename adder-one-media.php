<?php
/*
Plugin Name: Работа с медиабиблиотекой. Пример
Plugin URI: http://sawtech.ru
Description: Плагин, который демонстрирует как просто работать с медиабиблиотекой 
Author: Ildar Saribzhanov
Version: 1.0
Author URI: http://sawtech.ru/
*/

function my_plugin_enqueue_media()
{
	// Подключение АПИ для работы с медиабиблиотекой
	wp_enqueue_media();
	
	// Скрипт для выбора файла
	wp_enqueue_script('adder-one-media.js', plugins_url('/js/adder-one-media.js', __FILE__), array('jquery'));
}

add_action('admin_enqueue_scripts', 'my_plugin_enqueue_media');


/**
 * Создание блока метаполей для постов
 */
function adder_one_meta()
{
	add_meta_box('adder_one_meta', 'Прикрепленный файл', 'adder_one_meta_view', 'post');
}

add_action('add_meta_boxes', 'adder_one_meta');


/**
 * HTML код блока
 */
function adder_one_meta_view()
{
	global $post;
	
	// Если это пост отличный от необходимого, уйдем отсюда, и ничего не отобразим
	if ($post->post_type != 'post') {
		return;
	}
	
	// Используем nonce для верификации
	wp_nonce_field(plugin_basename(__FILE__), 'adder_one_nonce');
	
	// Заберем значение прикрепленного файла
	$adding_file_id = get_post_meta($post->ID, 'adding_file_id', true);
	
	// Ссылка на добавление файлов, если js отколючен
	$upload_link = esc_url(get_upload_iframe_src('null', $post->ID));
	
	// Поле для выбора файла
	echo '
	<div class="custom_field_itm">
		<div class="js-adding-wrap">';
	
	if ($adding_file_id) :
		$file_info = get_post($adding_file_id);
		$file_icon = wp_get_attachment_image(706, 'thumbnail', true);
		
		echo '<div class="add_file">
			<input type="hidden" name="adding_file_id" value="' . $adding_file_id . '" />
			<div class="add_file_icon">' . $file_icon . '</div>
			<p class="add_file_name">' . $file_info->post_title . '</p>
		</div>';
	endif;
	
	echo '</div><br/>
		<a href="' . $upload_link . '" class="button button-primary button-large js-add-file">Добавить файл</a>
	</div>';
}