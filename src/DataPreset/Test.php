<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для тестового анонса.
 * @package Service\AnnouncementTemplate\DataPreset
 */
abstract class Test extends Base {

	/** @var bool является ли блокирующий */
	protected const _IS_GLOBAL = true;

	/**
	 * @inheritDoc
	 */
	public static function create(array $data = []):array {

		return [
			"is_global"             => static::_IS_GLOBAL,
			"type"                  => static::_resolveNumericType(),
			"status"                => \Service\AnnouncementTemplate\AnnouncementStatus::ACTIVE,
			"company_id"            => 0,
			"priority"              => 100,
			"expires_at"            => time() + 60,
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

		$extra = static::initExtra();
		$extra["extra"]["unique"] = (string) ($data["unique"] ?? rand(0, 1000000));

		return $extra;
	}
}
