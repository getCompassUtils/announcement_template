<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса оповещения новой версии.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class AppVersionAvailableAndroid extends Base {

	/** @var int как часто нужно сбрасывать прочтение анонса */
	protected const _REPEAT_PERIOD = 3 * 24 * 60 * 60;

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"meta"         => [
				"platform"               => "android",
				"supported_code_version" => "<1000000",
			],

			"changelog"    => "we got something interesting for you",
			"version"      => "",
			"release_data" => 0,
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
			"resend_repeat_time"    => static::_REPEAT_PERIOD,
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

		// устанавливаем описание
		$extra["extra"]["changelog"]    = $data["changelog"] ?? "";
		$extra["extra"]["version"]      = $data["version"] ?? "0";
		$extra["extra"]["release_data"] = $data["release_data"] ?? time();

		// устанавливаем правило версии
		$extra["extra"]["meta"]["supported_code_version"] = "<" . ($data["supported_code_version"] ?? 1000000);

		return $extra;
	}
}
