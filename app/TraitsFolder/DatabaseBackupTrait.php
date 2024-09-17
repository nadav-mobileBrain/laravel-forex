<?php

namespace App\TraitsFolder;

use Exception;

trait DatabaseBackupTrait
{
    public function __construct()
    {
        setlocale(LC_ALL, 'en_US.UTF8');
    }

    /**
     * @return mixed
     */
    public static function DatabaseBackupName()
    {
        /*
         * Back Up Action
         * Save Database name
         * return to Backup page
         * */
        try {
            $host = (string) env('DB_HOST');
            $username = (string) env('DB_USERNAME');
            $password = (string) env('DB_PASSWORD');
            $database = (string) env('DB_DATABASE');
            $con = mysqli_connect($host, $username, $password, $database);
            if (!$con) {
                echo "Connection Fail";
            }

            $tables = [];
            $result = mysqli_query($con, "SHOW TABLES");

            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }

            $return = '';
            foreach ($tables as $table) {
                $result = mysqli_query($con, 'SELECT * FROM ' . $table);
                $num_fields = mysqli_num_fields($result);

                $row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE ' . $table));
                $return .= "\n\n" . str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $row2[1]) . ";\n\n";

                for ($i = 0; $i < $num_fields; $i++) {
                    while ($row = mysqli_fetch_row($result)) {
                        $return .= 'INSERT INTO ' . $table . ' VALUES(';
                        for ($j = 0; $j < $num_fields; $j++) {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                            if (isset($row[$j])) {
                                $return .= '"' . $row[$j] . '"';
                            } else {
                                $return .= '""';
                            }if ($j < ($num_fields - 1)) {
                                $return .= ',';
                            }
                        }
                        $return .= ");\n";
                    }
                }
                $return .= "\n\n\n";
            }

            $backup_name = 'backup-' . date('Y-m-d-H-i-s') . '.sql';

            $handle = fopen(storage_path("database-backup") . '/' . $backup_name, 'w+');
            fwrite($handle, $return);
            fclose($handle);

            return $backup_name;
        } catch (Exception $e) {
            \session()->flash('message', $e->getMessage());
            \session()->flash('type', 'warning');

            return redirect()->back();
        }
    }

    /**
     * @param $filename
     */
    public static function DatabaseDownload($filename)
    {
        $backup_loc = storage_path('database-backup/' . $filename);
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");
        readfile($backup_loc);
        exit();
    }
}
