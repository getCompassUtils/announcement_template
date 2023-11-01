<?php

namespace Service\AnnouncementTemplate\DataPreset;

/**
 * Создает инициирующие данные для глобального блокирующего анонса.
 * @package Service\AnnouncementTemplate\DataPreset
 */
class TestGlobalBlocking extends Test {

	/** @var bool является ли блокирующий */
	protected const _IS_GLOBAL = true;

	/** @var int текущая версия экстры */
	protected const _EXTRA_VERSION = 1;

	/** @var int данные для экстры */
	protected const _EXTRA_SCHEMA = [

		self::_EXTRA_VERSION => [
			"unique" => ""
		],
	];
}
