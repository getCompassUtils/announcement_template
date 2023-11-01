<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса о очистке компании.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class CompanyWasPurged extends Base {

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"are_conversations_purged" => 1,
			"are_files_purged"         => 1,
			"purged_at"                => 0,
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

		// что было очищено
		$extra["extra"]["are_conversations_purged"] = $data["are_conversations_purged"] ?? 1;
		$extra["extra"]["are_files_purged"]         = $data["are_files_purged"] ?? 1;

		// когда была почищена компания
		$extra["extra"]["purged_at"] = $data["purged_at"] ?? time();

		return $extra;
	}
}
