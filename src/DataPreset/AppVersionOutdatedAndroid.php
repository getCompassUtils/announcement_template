<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса неподдерживаемой версии.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class AppVersionOutdatedAndroid extends Base {

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"meta" => [
				"platform"               => "android",
				"supported_code_version" => "<1000000",
			],
		],
	];

	/**
	 * @inheritDoc
	 */
	public static function create(array $data = []):array {

		return [
			"is_global"             => true,
			"type"                  => static::_resolveNumericType(),
			"status"                => \Service\AnnouncementTemplate\AnnouncementStatus::ACTIVE,
			"company_id"            => 0,
			"priority"              => 0,
			"expires_at"            => 0,
			"resend_repeat_time"    => 0,
			"receiver_user_id_list" => [],
			"excluded_user_id_list" => [],
			"extra"                 => static::_initExtra($data)
		];
	}

	/**
	 * Создает уникальную экстру.
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected static function _initExtra(array $data):array {

		// инициализируем экстру
		$extra = static::initExtra();

		// устанавливаем правило версии
		$extra["extra"]["meta"]["supported_code_version"] = "<" . ($data["supported_code_version"] ?? 1000000);

		return $extra;
	}
}
