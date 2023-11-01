<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса о необходимости оплаты пространства
 * @package Service\AnnouncementTemplate\DataPreset
 */
class SpaceTariffExpiration extends Base {

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"plan_expiration_date_list" => [
				"member_count"       => 0,
				"space_storage_size" => 0,
			],
		],
	];

	/** @var int Период переотправки анонса */
	protected const _RESEND_REPEAT_TIME = 60 * 60 * 12; // 12 часов

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
			"expires_at"            => $data["expires_at"] ?? 0,
			"resend_repeat_time"    => $data["resend_repeat_time"] ?? self::_RESEND_REPEAT_TIME,
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

		// когда заканчивается время оплаты
		if (isset($data["plan_expiration_date_list"]["member_count"])) {
			$extra["extra"]["plan_expiration_date_list"]["member_count"] = $data["plan_expiration_date_list"]["member_count"];
		}

		if (isset($data["plan_expiration_date_list"]["file_storage_size"])) {
			$extra["extra"]["plan_expiration_date_list"]["file_storage_size"] = $data["plan_expiration_date_list"]["file_storage_size"];
		}

		return $extra;
	}
}
