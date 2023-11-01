<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для анонса о том, что тарифный план окончен
 * @package Service\AnnouncementTemplate\DataPreset
 */
class SpaceTariffExpired extends Base {

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [

			"expired_plan_list"      => [],
			"restricted_access_from" => 0,
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

		$extra["extra"]["expired_plan_list"]      = $data["expired_plan_list"] ?? [];
		$extra["extra"]["restricted_access_from"] = $data["restricted_access_from"] ?? 0;

		return $extra;
	}
}
