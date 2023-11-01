<?php

namespace Service\AnnouncementTemplate;

/**
 *
 */
interface TemplateInterface {

	/**
	 * @return array
	 */
	public function getSchema():array;

	/**
	 * @return array
	 */
	public function getProperties():array;
}