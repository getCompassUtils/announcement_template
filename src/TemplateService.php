<?php

namespace Service\AnnouncementTemplate;

/**
 *
 */
class TemplateService {

	/**
	 * Создает анонс с указанным типом.
	 *
	 * @param int   $type
	 * @param array $data
	 *
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	public static function createOfType(int $type, array $data = []):array {

		$preset = DataPreset\Resolver::createByNumericType($type, $data);
		return static::createAnnouncementFromTemplate($preset);
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws \Exception
	 *
	 * @long
	 */
	public static function createAnnouncementFromTemplate(array $data):array {

		$map = include "template_map.php";

		if (!isset($data["type"])) {
			throw new \Exception("Type is required");
		}

		if (!isset($data["status"])) {
			throw new \Exception("Status is required");
		}

		// проверяем, что тип подходит
		if (!AnnouncementType::isValid($data["type"])) {
			throw new \Exception("passed unknown announcement type");
		}

		// проверяем, что статус подходит
		if (!AnnouncementStatus::isValid($data["status"])) {
			throw new \Exception("passed unknown announcement status");
		}

		$announcement_type   = $data["type"];
		$announcement_status = $data["status"];

		/** @var TemplateInterface $template */
		$template  = new $map[$announcement_type]["class"]($announcement_type);
		$validator = ArraySchemaValidator::createAndValidate($template->getSchema(), $data);

		if ($validator->hasErrors()) {

			// сделать отдельное исключение со списком ошибок
			throw new \Exception("Has errors");
		}

		$is_global             = false;
		$company_id            = 0;
		$priority              = 0;
		$expires_at            = 0;
		$resend_repeat_time    = 0;
		$receiver_user_id_list = [];
		$excluded_user_id_list = [];
		$extra                 = [];

		foreach ($data as $key => $value) {

			switch ($key) {
				case "is_global":
					$is_global = $value;
					break;
				case "company_id":
					$company_id = $value;
					break;
				case "priority":
					$priority = $value;
					break;
				case "expires_at":
					$expires_at = $value;
					break;
				case "resend_repeat_time":
					$resend_repeat_time = $value;
					break;
				case "receiver_user_id_list":
					$receiver_user_id_list = $value;
					break;
				case "excluded_user_id_list":
					$excluded_user_id_list = $value;
					break;
				case "extra":
					$extra = $value;
					break;
				default:
					$extra["extra"][$key] = $value;
					break;
			}
		}

		return [
			"is_global"             => $is_global,
			"type"                  => $announcement_type,
			"status"                => $announcement_status,
			"company_id"            => $company_id,
			"priority"              => $priority,
			"expires_at"            => $expires_at,
			"resend_repeat_time"    => $resend_repeat_time,
			"receiver_user_id_list" => $receiver_user_id_list,
			"excluded_user_id_list" => $excluded_user_id_list,
			"extra"                 => $extra,
		];
	}
}