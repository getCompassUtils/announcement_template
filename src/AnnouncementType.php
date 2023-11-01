<?php

namespace Service\AnnouncementTemplate;

/**
 * Класс для работы с известными типами анонсов.
 * Строковое название типа определяется названием константы в анонсе.
 *
 * @package Service\AnnouncementTemplate
 */
class AnnouncementType {

	/** @var array список кэшированных констант */
	protected static array $_constant_list = [];

	/** глобальные блокирующие 1-1000 */
	public const TEST_GLOBAL_BLOCKING = 1;

	// доступ к приложению
	public const APPLICATION_UNAVAILABLE                 = 11;
	public const APPLICATION_TECHNICAL_WORKS_IN_PROGRESS = 12;

	// версии приложения устарели
	public const APP_VERSION_OUTDATED_IOS      = 31;
	public const APP_VERSION_OUTDATED_ANDROID  = 32;
	public const APP_VERSION_OUTDATED_ELECTRON = 33;

	/** глобальные уведомляющие 1001-2000 */
	public const TEST_GLOBAL_NOTIFYING = 1001;

	// доступна новая версия приложения
	public const APP_VERSION_AVAILABLE_IOS      = 1031;
	public const APP_VERSION_AVAILABLE_ANDROID  = 1032;
	public const APP_VERSION_AVAILABLE_ELECTRON = 1033;

	/** компанейские блокирующие 2001-3000 */
	public const TEST_COMPANY_BLOCKING = 2001;

	// компания недоступна
	public const COMPANY_IS_IN_HIBERNATION_MODE      = 2011;
	public const COMPANY_TECHNICAL_WORKS_IN_PROGRESS = 2012;
	public const COMPANY_IS_PURGING                  = 2013;
	public const COMPANY_IS_MIGRATING                = 2014;
	public const COMPANY_IS_HIBERNATING              = 2015;

	/** компанейские уведомляющие 3001-4000 */
	public const TEST_COMPANY_NOTIFYING = 3001;

	public const COMPANY_WAS_PURGED             = 3011;
	public const COMPANY_WAS_DELETED            = 3012;
	public const COMPANY_TECHNICAL_WORKS_NOTICE = 3013;
	public const SPACE_TARIFF_EXPIRATION 	  = 3014;
	public const SPACE_TARIFF_EXPIRED           = 3015;

	/**
	 * Возвращает строковое имя для тип анонса.
	 *
	 * @param int $numeric_type
	 *
	 * @return string
	 */
	public static function resolveTextType(int $numeric_type):string {

		foreach (static::_getConstants() as $constant_name => $constant_value) {

			if ($constant_value === $numeric_type) {
				return strtolower($constant_name);
			}
		}

		throw new \InvalidArgumentException("passed unsupported numeric type");
	}

	/**
	 * Возвращает строковое имя для тип анонса.
	 *
	 * @param int $numeric_type
	 *
	 * @return string
	 */
	public static function resolveNumericType(string $string_type):string {

		$string_type = strtolower($string_type);

		foreach (static::_getConstants() as $constant_name => $constant_value) {

			if (strtolower($constant_name) === $string_type) {
				return strtolower($constant_value);
			}
		}

		throw new \InvalidArgumentException("passed unsupported string type");
	}

	/**
	 * Возвращает известные типы для шаблонов анонсов.
	 *
	 * @return array
	 */
	public static function getKnownStringTypes():array {

		$output = [];

		foreach (static::_getConstants() as $k => $v) {
			$output[strtolower($k)] = $v;
		}

		return $output;
	}

	/**
	 * Проверяет, является ли тип известным.
	 *
	 * @return bool
	 */
	public static function isValid(int $value):bool {

		return in_array($value, static::_getConstants());
	}

	/**
	 * Читает и кэширует данные константы.
	 *
	 * @return array
	 */
	protected static function _getConstants():array {

		if (count(static::$_constant_list) === 0) {

			$reflection             = new \ReflectionClass(static::class);
			static::$_constant_list = $reflection->getConstants();
		}

		return static::$_constant_list;
	}
}
