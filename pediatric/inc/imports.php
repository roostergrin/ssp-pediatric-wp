<?
if (isset($_GET['import-entity-phone-numbers'])) {
	add_action('wp', function() {
		global $wpdb;

		$numbers = [];
		$numbers[1] = [7158355182, 7158280206, 8009244606];
		$numbers[2] = [6087826778, 6087826778, NULL];
		$numbers[3] = [6082226377, 6082226377, NULL];
		$numbers[4] = [7154215255, NULL, NULL];
		$numbers[5] = [7155262544, NULL, NULL];
		$numbers[6] = [7158355182, 7158280206, 8009244606];
		$numbers[7] = [7158355182, 7158280206, 8009244606];
		$numbers[8] = [7158355182, 7158280206, 8009244606];
		$numbers[9] = [7158355182, 7158280206, 8009244606];
		$numbers[10] = [7158355182, 7158280206, 8009244606];
		$numbers[11] = [7158355182, 7158280206, 8009244606];
		$numbers[12] = [7158355182, 7158280206, 8009244606];
		$numbers[13] = [7158355182, 7158280206, 8009244606];
		$numbers[14] = [7158425459, 7158425459, 8009244606];
		$numbers[15] = [7155361339, 7155361339, 8009244606];
		$numbers[16] = [7157357666, 7159236200, 8009244606];
		$numbers[17] = [7152465165, 7158212173, NULL];
		$numbers[18] = [7152688109, 7158212173, NULL];
		$numbers[19] = [7156845858, 7158212173, NULL];
		$numbers[20] = [7152465165, 7158212173, NULL];
		$numbers[21] = [6087826778, NULL, NULL];
		$numbers[22] = [6087826778, NULL, NULL];
		$numbers[23] = [6087826778, NULL, NULL];
		$numbers[24] = [6087826778, NULL, NULL];
		$numbers[25] = [6517778182, NULL, NULL];
		$numbers[26] = [6517778182, NULL, NULL];
		$numbers[27] = [6082226377, NULL, NULL];
		$numbers[28] = [9208870377, NULL, NULL];
		$numbers[29] = [6088343200, NULL, NULL];

		$wpdb->query('TRUNCATE EntityPhoneNumber');
		foreach ($numbers as $entityID => $n) {
			foreach ($n as $k => $number) {
				if (is_null($number)) continue;
				$wpdb->query($wpdb->prepare("
					INSERT INTO EntityPhoneNumber (entityID, phoneNumberTypeID, phoneNumber) VALUES (%d, %d, %d)
				", $entityID, $k + 1, $number));
			}
		}
	});
}

if (isset($_GET['import-offices'])) {
	add_action('wp', function() {
		global $wpdb;
		$row = 0;
		if (($handle = fopen(get_stylesheet_directory() . '/import/Office.tsv', 'r')) !== false) {
			$wpdb->query('TRUNCATE Office');
			while (($data = fgetcsv($handle, 0, "\t")) !== false) {
				$num = count($data);
				echo "<p>$num fields in line $row</p>\n";
				if ($row === 0) {
					$row++;
					continue;
				}
				$row++;
				$insert_row = [
					'%d',
					'%d',
					($data[2] == 'NULL' ? 'NULL' : "'{$data[2]}'"),
					($data[3] == 'NULL' ? 'NULL' : "'{$data[3]}'"),
					($data[4] == 'NULL' ? 'NULL' : "'{$data[4]}'"),
					($data[5] == 'NULL' ? 'NULL' : "'{$data[5]}'"),
					($data[6] == 'NULL' ? 'NULL' : "'{$data[6]}'"),
					($data[7] == 'NULL' ? 'NULL' : "'{$data[7]}'"),
					($data[8] == 'NULL' ? 'NULL' : "{$data[8]}"),
					($data[9] == 'NULL' ? 'NULL' : "'{$data[9]}'"),
					($data[10] == 'NULL' ? 'NULL' : "'{$data[10]}'"),
					($data[11] == 'NULL' ? 'NULL' : "'{$data[11]}'"),
					($data[12] == 'NULL' ? 'NULL' : "'{$data[12]}'"),
					($data[13] == 'NULL' ? 'NULL' : "'{$data[13]}'"),
					($data[14] == 'NULL' ? 'NULL' : "'{$data[14]}'"),
					($data[15] == 'NULL' ? 'NULL' : "'{$data[15]}'"),
					($data[16] == 'NULL' ? 'NULL' : "{$data[16]}"),
					($data[17] == 'NULL' ? 'NULL' : "'{$data[17]}'"),
					($data[18] == 'NULL' ? 'NULL' : "'{$data[18]}'"),
				];
				$row = implode(', ', $insert_row);
				$sql = $wpdb->prepare("
				INSERT INTO Office
					(ID, entityID, regionID, storeCode, address1, address2, address3, city, stateID, zip, latitude, longitude, openDate, closeDate, redirectEntityID, directions, timeZoneID, logo, logoKnockout)
				VALUES
					($row)
				", $data[0], $data[1]);
				// print_stmt($sql, 1);
				$wpdb->query($sql);
			}
			fclose($handle);
		}

		// $wpdb->query('TRUNCATE OfficeHours');
		// foreach ($hours as $row) {
		// 	$wpdb->query($wpdb->prepare("
		// 		INSERT INTO OfficeHours (entityID, day, open, close) VALUES (%d, %s, %s, %s)
		// 	", $entityID, $k + 1, $number));
		// }
	});
}

if (isset($_GET['import-entity-office-hours'])) {
	add_action('wp', function() {
		global $wpdb;

		$hours = [];
		$hours[] = [1, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [1, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [1, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [1, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [1, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [2, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [2, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [2, 'WEDNESDAY', '7:30:00', '17:00:00'];
		$hours[] = [2, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [3, 'MONDAY', '8:00:00', '17:40:00'];
		$hours[] = [3, 'TUESDAY', '8:00:00', '17:40:00'];
		$hours[] = [3, 'WEDNESDAY', '8:00:00', '17:40:00'];
		$hours[] = [3, 'THURSDAY', '8:00:00', '17:40:00'];
		$hours[] = [3, 'FRIDAY', '8:00:00', '12:00:00'];
		$hours[] = [4, 'MONDAY', '8:00:00', '16:30:00'];
		$hours[] = [4, 'TUESDAY', '8:00:00', '16:30:00'];
		$hours[] = [4, 'WEDNESDAY', '8:00:00', '16:30:00'];
		$hours[] = [4, 'THURSDAY', '8:00:00', '16:30:00'];
		$hours[] = [4, 'FRIDAY', '8:00:00', '16:30:00'];
		$hours[] = [5, 'MONDAY', '8:00:00', '16:00:00'];
		$hours[] = [5, 'TUESDAY', '8:00:00', '16:00:00'];
		$hours[] = [5, 'WEDNESDAY', '8:00:00', '16:00:00'];
		$hours[] = [5, 'THURSDAY', '8:00:00', '16:00:00'];
		$hours[] = [6, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [6, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [6, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [6, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [6, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [7, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [7, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [7, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [7, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [7, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [8, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [8, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [8, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [8, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [8, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [9, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [9, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [9, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [9, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [9, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [10, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [10, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [10, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [10, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [10, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [11, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [11, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [11, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [11, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [11, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [12, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [12, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [12, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [12, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [12, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [13, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [13, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [13, 'WEDNESDAY', '7:30:00', '18:00:00'];
		$hours[] = [13, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [13, 'FRIDAY', '7:30:00', '17:00:00'];
		$hours[] = [14, 'MONDAY', '8:00:00', '16:30:00'];
		$hours[] = [14, 'TUESDAY', '8:00:00', '16:30:00'];
		$hours[] = [14, 'WEDNESDAY', '8:00:00', '16:30:00'];
		$hours[] = [14, 'THURSDAY', '8:00:00', '16:30:00'];
		$hours[] = [14, 'FRIDAY', '8:00:00', '16:30:00'];
		$hours[] = [16, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [16, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [16, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [16, 'THURSDAY', '8:00:00', '16:00:00'];
		$hours[] = [17, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [17, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [17, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [17, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [17, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [18, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [18, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [18, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [18, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [18, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [19, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [19, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [19, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [19, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [19, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [20, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [20, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [20, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [20, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [20, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [21, 'MONDAY', '7:30:00', '17:00:00'];
		$hours[] = [21, 'TUESDAY', '7:30:00', '17:00:00'];
		$hours[] = [21, 'WEDNESDAY', '7:30:00', '17:00:00'];
		$hours[] = [21, 'THURSDAY', '7:30:00', '17:00:00'];
		$hours[] = [25, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [25, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [25, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [25, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [25, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [26, 'MONDAY', '8:00:00', '17:00:00'];
		$hours[] = [26, 'TUESDAY', '8:00:00', '17:00:00'];
		$hours[] = [26, 'WEDNESDAY', '8:00:00', '17:00:00'];
		$hours[] = [26, 'THURSDAY', '8:00:00', '17:00:00'];
		$hours[] = [26, 'FRIDAY', '8:00:00', '15:00:00'];
		$hours[] = [27, 'MONDAY', '8:00:00', '17:40:00'];
		$hours[] = [27, 'TUESDAY', '8:00:00', '17:40:00'];
		$hours[] = [27, 'WEDNESDAY', '8:00:00', '17:40:00'];
		$hours[] = [27, 'THURSDAY', '8:00:00', '17:40:00'];
		$hours[] = [27, 'FRIDAY', '8:00:00', '12:00:00'];

		$wpdb->query('TRUNCATE OfficeHours');
		foreach ($hours as $row) {
			$sql = $wpdb->prepare("
				INSERT INTO OfficeHours (entityID, day, open, close) VALUES (%d, %s, %s, %s)
			", $row[0], $row[1], $row[2], $row[3]);
			$wpdb->query($sql);
		}
	});
}

if (isset($_GET['import-entity-phone-numbers'])) {
	add_action('wp', function() {
		global $wpdb;

		$phone_numbers = [];
		$phone_numbers[1] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[2] = [6087826778, 6087826778, false];
		$phone_numbers[3] = [6082226377, 6082226377, false];
		$phone_numbers[4] = [7154215255, false, false];
		$phone_numbers[5] = [7155262544, false, false];
		$phone_numbers[6] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[7] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[8] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[9] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[10] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[11] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[12] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[13] = [7158355182, 7158280206, 8009244606];
		$phone_numbers[14] = [7158425459, 7158425459, 8009244606];
		$phone_numbers[15] = [7155361339, 7155361339, 8009244606];
		$phone_numbers[16] = [7157357666, 7159236200, 8009244606];
		$phone_numbers[17] = [7152465165, 7158212173, false];
		$phone_numbers[18] = [7152688109, 7158212173, false];
		$phone_numbers[19] = [7156845858, 7158212173, false];
		$phone_numbers[20] = [7152465165, 7158212173, false];
		$phone_numbers[21] = [6087826778, false, false];
		$phone_numbers[22] = [6087826778, false, false];
		$phone_numbers[23] = [6087826778, false, false];
		$phone_numbers[24] = [6087826778, false, false];
		$phone_numbers[25] = [6517778182, false, false];
		$phone_numbers[26] = [6517778182, false, false];
		$phone_numbers[27] = [6082226377, false, false];
		$phone_numbers[28] = [9208870377, false, false];
		$phone_numbers[29] = [6088343200, false, false];

		$wpdb->query('TRUNCATE EntityPhoneNumber');
		foreach ($phone_numbers as $entityID => $row) {
			foreach ($row as $k => $phone_number) {
				if (!$phone_number) continue;
				$typeID = $k + 1;
				$sql = $wpdb->prepare("
					INSERT INTO EntityPhoneNumber (entityID, phoneNumberTypeID, phoneNumber) VALUES (%d, %d, %d)
				", $entityID, $typeID, $phone_number);
				// print_stmt($sql, 1);
				$wpdb->query($sql);
			}
		}
	});
}

if (isset($_GET['import-entity-email-addresses'])) {
	add_action('wp', function() {
		global $wpdb;
		$row = 0;
		if (($handle = fopen(get_stylesheet_directory() . '/import/EntityEmailAddress.tsv', 'r')) !== false) {
			$wpdb->query('TRUNCATE EntityEmailAddress');
			while (($data = fgetcsv($handle, 0, "\t")) !== false) {
				$num = count($data);
				echo "<p>$num fields in line $row</p>\n";
				if ($row === 0 || empty($data[2]) || !strstr($data[2], '@')) {
					$row++;
					continue;
				}
				$row++;
				$insert_row = [
					'%d',
					'%d',
					'%s',
				];
				$sql = $wpdb->prepare("
				INSERT INTO EntityEmailAddress
					(entityID, emailAddressTypeID, emailAddress)
				VALUES
					(".implode(', ', $insert_row).")
				", $data[0], $data[1], $data[2]);
				// print_stmt($sql, 1);
				$wpdb->query($sql);
			}
			fclose($handle);
		}

		// $wpdb->query('TRUNCATE OfficeHours');
		// foreach ($hours as $row) {
		// 	$wpdb->query($wpdb->prepare("
		// 		INSERT INTO OfficeHours (entityID, day, open, close) VALUES (%d, %s, %s, %s)
		// 	", $entityID, $k + 1, $number));
		// }
	});
}

if (isset($_GET['import-geographic-regions'])) {
	add_action('wp', function() {
		global $wpdb;
		$row = 0;
		if (($handle = fopen(get_stylesheet_directory() . '/import/GeographicRegions.tsv', 'r')) !== false) {
			$truncated = $wpdb->query('TRUNCATE GeographicRegions');
			while (($data = fgetcsv($handle, 0, "\t")) !== false) {
				$num = count($data);
				echo "<p>$num fields in line $row</p>\n";
				if ($row === 0) {
					$row++;
					continue;
				}
				$row++;
				$insert_row = [
					'%s',
					'%s',
					'%s',
				];
				$sql = $wpdb->prepare("
				INSERT INTO GeographicRegions
					(name, abbreviation, geoID)
				VALUES
					(".implode(', ', $insert_row).")
				", $data[1], $data[2], $data[3]);
				$output = $wpdb->query($sql);
			}
			$data = fgetcsv($handle, 0, "\t");
			fclose($handle);
		} else {
			die('failed to open file: ' . get_stylesheet_directory() . '/import/GeographicRegions.tsv');
		}

		// $wpdb->query('TRUNCATE OfficeHours');
		// foreach ($hours as $row) {
		// 	$wpdb->query($wpdb->prepare("
		// 		INSERT INTO OfficeHours (entityID, day, open, close) VALUES (%d, %s, %s, %s)
		// 	", $entityID, $k + 1, $number));
		// }
	});
}

if (isset($_GET['import-office-geographic-regions'])) {
	add_action('wp', function() {
		global $wpdb;
		$row = 0;
		if (($handle = fopen(get_stylesheet_directory() . '/import/OfficeGeographicRegions.tsv', 'r')) !== false) {
			$wpdb->query('TRUNCATE OfficeGeographicRegions');
			while (($data = fgetcsv($handle, 0, "\t")) !== false) {
				$num = count($data);
				echo "<p>$num fields in line $row</p>\n";
				if ($row === 0) {
					$row++;
					continue;
				}
				$row++;
				$insert_row = [
					'%d',
					'%d',
					'%d',
				];
				$sql = $wpdb->prepare("
				INSERT INTO OfficeGeographicRegions
					(officeID, entityID, geographicRegionID)
				VALUES
					(".implode(', ', $insert_row).")
				", $data[0], $data[1], $data[2]);
				// print_stmt($sql, 1);
				$wpdb->query($sql);
			}
			fclose($handle);
		}

		// $wpdb->query('TRUNCATE OfficeHours');
		// foreach ($hours as $row) {
		// 	$wpdb->query($wpdb->prepare("
		// 		INSERT INTO OfficeHours (entityID, day, open, close) VALUES (%d, %s, %s, %s)
		// 	", $entityID, $k + 1, $number));
		// }
	});
}

