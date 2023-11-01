<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Интерфейс класса для создания готового анонса из шаблона.
 * @package Service\AnnouncementTemplate\DataPreset
 */
interface DataPresetInterface {

	/**
	 * Создать анонс из пресета.
	 * @return array
	 */
	public static function create(array $data = []):array;

	/**
	 * Инициализирует пустую экстру.
	 * @return array
	 */
	public static function initExtra():array;
}
