<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Вспомогательный класс для получения шаблона для анонса по его типу.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class Resolver {

	/**
	 * Возвращает данные пресета анонса по его числовому типу.
	 *
	 * @param int $type
	 * @param array $data
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public static function createByNumericType(int $type, array $data = []):array {

		return static::createByStringType(\Service\AnnouncementTemplate\AnnouncementType::resolveTextType($type), $data);
	}

	/**
	 * Возвращает данные пресета анонса по его числовому типу.
	 *
	 * @param int   $text_type
	 * @param array $data
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public static function createByStringType(string $text_type, array $data = []):array {

		/** @var DataPresetInterface $class_name */
		$class_name = static::_resolveClassName($text_type);

		return $class_name::create($data);
	}

	/**
	 * Создает пустую эктру для указанного типа анонса.
	 *
	 * @return array
	 */
	public static function createDummyExtra(int $type):array {

		$text_type = \Service\AnnouncementTemplate\AnnouncementType::resolveTextType($type);

		/** @var DataPresetInterface $class_name */
		$class_name = static::_resolveClassName($text_type);

		return $class_name::initExtra();
	}

	/**
	 * Возвращает имя класса для пресета анонса.
	 *
	 * @return string
	 */
	protected static function _resolveClassName(string $text_type):string {

		$class_words = explode("_", strtolower($text_type));

		foreach ($class_words as $k => $word) {
			$class_words[$k] = ucfirst($word);
		}

		$class_name = implode("", $class_words);
		$class_name = "\\" . __NAMESPACE__ . "\\{$class_name}";

		/** @var DataPresetInterface $class_name */

		// проверяем, что такой класс существует
		if (class_exists($class_name)) {
			return $class_name;
		}

		throw new \InvalidArgumentException("there is no preset for type {$text_type}");
	}
}
