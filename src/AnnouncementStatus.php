<?php

namespace Service\AnnouncementTemplate;

/**
 * Класс для работы со статусами анонсов.
 *
 * @package Service\AnnouncementTemplate
 */
class AnnouncementStatus {

	/** @var int анонс активен */
	public const ACTIVE = 1;

	/** @var int анонс устарел */
	public const INACTIVE = 11;

	/**
	 * Проверяет, является ли статус валидным.
	 *
	 * @param int $status
	 *
	 * @return bool
	 */
	public static function isValid(int $status):bool {

		return $status === static::ACTIVE || $status === static::INACTIVE;
	}
}
