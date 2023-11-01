<?php

use Service\AnnouncementTemplate\DefaultTemplate;
use Service\AnnouncementTemplate\AnnouncementType;

$output = [

	0 => [
		"id"    => 0,
		"class" => DefaultTemplate::class,
	],
];

foreach (AnnouncementType::getKnownStringTypes() as $type => $numeric_type) {

	$output[$numeric_type] = [
		"id"    => $numeric_type,
		"class" => DefaultTemplate::class,
	];
}

return $output;