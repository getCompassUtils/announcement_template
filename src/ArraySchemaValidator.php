<?php

namespace Service\AnnouncementTemplate;

/**
 * Валидирует схему массива
 */
class ArraySchemaValidator {

	/**
	 * @var array
	 */
	private array $errors = [];

	/**
	 * Валидация массива используя схему
	 *
	 * @param array $schema
	 * @param array $data
	 */
	public function validate(array $schema, array $data):void {

		$this->errors = [];

		// Проверка, что все обязательные поля присутствуют
		if (isset($schema["required"])) {
			foreach ($schema["required"] as $property_name) {
				if (!isset($data[$property_name])) {
					$this->errors[$property_name] = "property is required";
				}
			}

			if ([] !== $this->errors) {
				return;
			}
		}

		foreach ($data as $key => $value) {

			if (!isset($schema["properties"][$key])) {
				if (isset($schema["required"]) && in_array($key, $schema["required"])) {
					$this->errors[$key] = "property is required but not specified";
				} else {
					$this->errors[$key] = "property is not allowed";
				}

				continue;
			}

			$property = $schema["properties"][$key];

			if (!$this->_typeValidation($property["type"], $value)) {

				$this->errors[$key] = "property has invalid type, expected {$property["type"]}, actual" . gettype($value);
				continue;
			}

			if ("array" === $property["type"] && isset($property["schema"])) {

				// если это не ассоциативный массив - не проверяем
				if (array_keys($value) === range(0, count($value) - 1)) {
					continue;
				}

				$validator = new ArraySchemaValidator();
				$validator->validate($property["schema"], $value);
				if ($validator->hasErrors()) {
					$this->errors[$key]["errors"] = $validator->getErrors();
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public function hasErrors():bool {

		return [] !== $this->errors;
	}

	/**
	 * @return array
	 */
	public function getErrors():array {

		return $this->errors;
	}

	/**
	 * @param array $schema
	 * @param array $data
	 *
	 * @return ArraySchemaValidator
	 */
	public static function createAndValidate(array $schema, array $data):ArraySchemaValidator {

		$validator = new ArraySchemaValidator();
		$validator->validate($schema, $data);

		return $validator;
	}

	/**
	 * @param string $type
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	protected function _typeValidation(string $type, mixed $value):bool {

		return match ($type) {
			"int"    => is_int($value),
			"string" => is_string($value),
			"array"  => is_array($value),
			"bool"   => is_bool($value),
			"float"  => is_int($value) || is_float($value),
			default  => false,
		};
	}
}