<?php

namespace Service\AnnouncementTemplate;

/**
 *
 */
class DefaultTemplate implements TemplateInterface {

	/**
	 * DefaultTemplate constructor.
	 */
	public function __construct(public int $type = 0) {

	}

	/**
	 * Получить имена всех атрибутов
	 * @return array
	 */
	public function getProperties():array {

		return array_keys($this->getSchema()["properties"]);
	}

	/**
	 * Возвращает полную схему для валидатора.
	 * @return array
	 * @long
	 */
	public function getSchema():array {

		return $this->_getCommonAnnouncementSchema();
	}

	/**
	 * Возвращает дефолтную для всех анонсов схему.
	 *
	 * @return array
	 * @long
	 */
	public function _getCommonAnnouncementSchema():array {

		return [
			"properties" => [
				"type"                  => [
					"type" => "int",
				],
				"status"                => [
					"type" => "int",
				],
				"is_global"             => [
					"type" => "bool",
				],
				"company_id"            => [
					"type" => "int",
				],
				"priority"              => [
					"type" => "int",
				],
				"expires_at"            => [
					"type" => "int",
				],
				"resend_repeat_time"    => [
					"type" => "int",
				],
				"receiver_user_id_list" => [
					"type" => "array",
				],
				"excluded_user_id_list" => [
					"type" => "array",
				],
				"extra"                 => [
					"type"   => "array",
					"schema" => [
						"required"   => [
							"version",
							"extra",
						],
						"properties" => [
							"version" => [
								"type" => "int",
							],
							"extra"   => [
								"type"   => "array",
								"schema" => $this->_getExtraSchema(),
							],
						],
					],
				],
			],
		];
	}

	/**
	 * Возвращает специфическую экстру для конкретного анонса.
	 *
	 * @return array
	 * @long
	 */
	protected function _getExtraSchema():array {

		$extra = [
			"required"   => [],
			"properties" => [],
		];

		if ($this->type !== 0) {

			try {
				$announcement_preset_extra = DataPreset\Resolver::createDummyExtra($this->type);
			} catch (\InvalidArgumentException) {
				return $extra;
			}

			$extra = $this->_makeArraySchema($announcement_preset_extra["extra"]);
		}

		return $extra;
	}

	/**
	 * Рекурсивно строит схему для массивов.
	 *
	 * @param array $data
	 *
	 * @return array[]
	 */
	protected function _makeArraySchema(array $data):array {

		$schema = [
			"required"   => [],
			"properties" => [],
		];

		foreach ($data as $field => $value) {

			$type = $this::_resolveFieldType($value);

			$schema["required"][]         = $field;
			$schema["properties"][$field] = [
				"type" => $type,
			];

			if ($type === "array") {
				$schema["properties"][$field]["schema"] = $this->_makeArraySchema($value);
			}
		}

		return $schema;
	}

	/**
	 * Получает строковой тип для валидатора по значению.
	 *
	 * @param mixed $value
	 *
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	protected static function _resolveFieldType(mixed $value):string {

		return match (gettype($value)) {
			"string" => "string",
			"double" => "float",
			"integer" => "int",
			"array" => "array",
			"boolean" => "bool",
			default => throw new \InvalidArgumentException("passed unsupported field type"),
		};
	}
}