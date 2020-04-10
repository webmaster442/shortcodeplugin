<?php
class CsvGenerator {
	public function Generate($csv, $delimiter=';') {
		ob_start();
		$hasheder = false;
		$lines = explode('<br />', $csv);
		if (count($lines) > 0) {
			echo "<table class=\"tablesorter\">\n";
			foreach ($lines as $line) {
				$line = trim($line);
				if (empty($line)) continue;
				$columns = explode($delimiter, $line);
				if (count($columns) > 0) {
					if ($hasheder) {
						echo "<tr>\n";
						foreach ($columns as $column) {
							echo "\t<td>".trim($column)."</td>\n";
						}
						echo "</tr>\n";
					}
					else {
						echo "<thead>\n<tr>\n";
						foreach ($columns as $column) {
							echo "\t<th>".trim($column)."</th>\n";
						}
						echo "</tr>\n</thead>\n<tbody>\n";
						$hasheder = true;
					}
				}
			}
			echo "</tbody></table>";
		}
		return ob_get_clean();
	}
}
?>