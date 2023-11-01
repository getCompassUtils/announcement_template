<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для глобального блокирующего анонса..
 * @package Service\AnnouncementTemplate\DataPreset
 */
class Base implements DataPresetInterface {

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

		],
	];

	/**
	 * @inheritDoc
	 */
	public static function create(array $data = []):array {

		return [
			"is_global"             => false,
			"type"                  => 0,
			"status"                => \Service\AnnouncementTemplate\AnnouncementStatus::INACTIVE,
			"company_id"            => 0,
			"priority"              => 0,
			"expires_at"            => 0,
			"resend_repeat_time"    => 0,
			"receiver_user_id_list" => [],
			"excluded_user_id_list" => [],
			"extra"                 => static::initExtra(),
		];
	}

	/**
	 * Создает экстру для анонса.
	 *
	 * @return array
	 */
	public static function initExtra():array {

		return [
			"version" => static::_EXTRA_VERSION,
			"extra"   => static::_EXTRA_SCHEMA[static::_EXTRA_VERSION],
		];
	}

	/**
	 * Актуализирует экстру для анонса.
	 *
	 * @return array
	 */
	public static function actualizeExtra(array $extra):array {

		if (!isset($extra["version"])) {
			return static::initExtra();
		}

		// сравниваем версию пришедшей extra с текущей
		if ($extra["version"] != static::_EXTRA_VERSION) {

			// сливаем текущую версию extra и ту, что пришла
			$extra["extra"]   = array_merge(static::_EXTRA_SCHEMA[static::_EXTRA_VERSION], $extra["extra"]);
			$extra["version"] = static::_EXTRA_VERSION;
		}

		return $extra;
	}

	/**
	 * Формирует строковое имя для типа анонса
	 *
	 * @return int
	 */
	protected static function _resolveNumericType():int {

		$name = (new \ReflectionClass(static::class))->getShortName();
		$name = strtolower(preg_replace("/(?<!^)[A-Z]/", "_$0", $name));

		return \Service\AnnouncementTemplate\AnnouncementType::resolveNumericType($name);
	}
}
