<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса о предстоящих тех. работах.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class CompanyTechnicalWorksInProgress extends Base {

	/** @var int дефолтная длительность работ */
	protected const _DEFAULT_PERIOD = 60 * 30;

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"started_at"           => 0,
			"will_be_available_at" => 0,
		],
	];

	/**
	 * @inheritDoc
	 */
	public static function create(array $data = []):array {

		return [
			"is_global"             => false,
			"type"                  => static::_resolveNumericType(),
			"status"                => \Service\AnnouncementTemplate\AnnouncementStatus::ACTIVE,
			"company_id"            => 0,
			"priority"              => 1,
			"expires_at"            => 0,
			"resend_repeat_time"    => 0,
			"receiver_user_id_list" => [],
			"excluded_user_id_list" => [],
			"extra"                 => static::_initExtra($data),
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
		$extra["extra"]["started_at"]           = $data["started_at"] ?? time();
		$extra["extra"]["will_be_available_at"] = $data["will_be_available_at"] ?? (time() + static::_DEFAULT_PERIOD);

		return $extra;
	}
}
